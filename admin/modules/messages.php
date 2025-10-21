<?php
$page_title = "Messages & Support";
require_once '../includes/header.php';

// Handle message actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $message_id = intval($_GET['id']);
    
    if ($_GET['action'] === 'mark_read') {
        $stmt = $pdo->prepare("UPDATE messages SET status = 'read' WHERE message_id = ?");
        $stmt->execute([$message_id]);
        log_admin_action("Message marked as read", "Message ID: $message_id");
        header('Location: messages.php?success=Message marked as read');
        exit();
    } elseif ($_GET['action'] === 'delete' && isset($_GET['confirm'])) {
        $stmt = $pdo->prepare("DELETE FROM messages WHERE message_id = ?");
        $stmt->execute([$message_id]);
        log_admin_action("Message deleted", "Message ID: $message_id");
        header('Location: messages.php?success=Message deleted');
        exit();
    }
}

// Handle reply
if (isset($_POST['send_reply'])) {
    $original_message_id = intval($_POST['original_message_id']);
    $reply_subject = "Re: " . sanitize_input($_POST['original_subject']);
    $reply_message = sanitize_input($_POST['reply_message']);
    $receiver_id = intval($_POST['sender_id']);
    
    $stmt = $pdo->prepare("INSERT INTO messages (sender_id, receiver_id, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['admin_id'], $receiver_id, $reply_subject, $reply_message]);
    
    // Mark original as read
    $stmt = $pdo->prepare("UPDATE messages SET status = 'read' WHERE message_id = ?");
    $stmt->execute([$original_message_id]);
    
    log_admin_action("Reply sent", "To user ID: $receiver_id");
    header('Location: messages.php?success=Reply sent successfully');
    exit();
}

// Fetch messages with filters
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';
$type_filter = $_GET['type'] ?? ''; // received or sent

if ($type_filter === 'sent') {
    $query = "
        SELECT m.*, u.first_name, u.last_name, u.email, u.profile_img,
               'sent' as message_type
        FROM messages m 
        JOIN users u ON m.receiver_id = u.user_id 
        WHERE m.sender_id = ?
    ";
    $params = [$_SESSION['admin_id']];
} else {
    $query = "
        SELECT m.*, u.first_name, u.last_name, u.email, u.profile_img,
               'received' as message_type
        FROM messages m 
        JOIN users u ON m.sender_id = u.user_id 
        WHERE m.receiver_id = ?
    ";
    $params = [$_SESSION['admin_id']];
}

if (!empty($search)) {
    $query .= " AND (m.subject LIKE ? OR m.message LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
}

if (!empty($status_filter) && $type_filter !== 'sent') {
    $query .= " AND m.status = ?";
    $params[] = $status_filter;
}

$query .= " ORDER BY m.sent_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistics
$total_messages = count($messages);
$unread_messages = array_filter($messages, fn($m) => $m['status'] === 'unread' && $m['message_type'] === 'received');
$received_messages = array_filter($messages, fn($m) => $m['message_type'] === 'received');
$sent_messages = array_filter($messages, fn($m) => $m['message_type'] === 'sent');
?>

<!-- Statistics Cards -->
<div class="row mb-4 animate-fade-in-up">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo $total_messages; ?></div>
                    <div class="stat-label">Total Messages</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-envelope"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo count($unread_messages); ?></div>
                    <div class="stat-label">Unread Messages</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-envelope-exclamation"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo count($received_messages); ?></div>
                    <div class="stat-label">Received</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-inbox"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo count($sent_messages); ?></div>
                    <div class="stat-label">Sent</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-send"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4 animate-slide-in-left">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-filter me-2"></i>Filters & Search
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search messages..."
                    value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <?php if ($type_filter !== 'sent'): ?>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="unread" <?php echo $status_filter === 'unread' ? 'selected' : ''; ?>>Unread</option>
                    <option value="read" <?php echo $status_filter === 'read' ? 'selected' : ''; ?>>Read</option>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-md-3">
                <select name="type" class="form-select" onchange="this.form.submit()">
                    <option value="received" <?php echo $type_filter !== 'sent' ? 'selected' : ''; ?>>Received Messages
                    </option>
                    <option value="sent" <?php echo $type_filter === 'sent' ? 'selected' : ''; ?>>Sent Messages</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="messages.php" class="btn btn-secondary w-100 mt-2">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Messages List -->
<div class="row">
    <div class="col-lg-12">
        <div class="card animate-slide-in-left">
            <div class="card-header d-flex justify-content-between align-items-center">
                <?php if ($type_filter === 'sent'): ?>
                <h5 class="card-title mb-0">
                    <i class="bi bi-send me-2"></i>Sent Messages (<?php echo count($sent_messages); ?>)
                </h5>
                <?php else: ?>
                <h5 class="card-title mb-0">
                    <i class="bi bi-inbox me-2"></i>Inbox (<?php echo count($received_messages); ?>)
                    <?php if (count($unread_messages) > 0): ?>
                    <span class="badge bg-danger ms-2"><?php echo count($unread_messages); ?> unread</span>
                    <?php endif; ?>
                </h5>
                <?php endif; ?>
                <div>
                    <?php if ($type_filter !== 'sent'): ?>
                    <a href="?type=sent" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-send me-1"></i> View Sent
                    </a>
                    <?php else: ?>
                    <a href="?type=received" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-inbox me-1"></i> View Inbox
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($messages as $message): ?>
                    <div
                        class="list-group-item list-group-item-action <?php echo $message['status'] === 'unread' && $message['message_type'] === 'received' ? 'bg-light' : ''; ?>">
                        <div class="d-flex w-100 justify-content-between">
                            <div class="d-flex align-items-center flex-grow-1">
                                <?php if ($message['message_type'] === 'received'): ?>
                                <img src="../uploads/profiles/<?php echo htmlspecialchars($message['profile_img']); ?>"
                                    alt="Profile" class="rounded-circle me-3" width="50" height="50"
                                    style="object-fit: cover;">
                                <?php else: ?>
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                    style="width: 50px; height: 50px;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <?php endif; ?>

                                <div class="flex-grow-1">
                                    <h6 class="mb-1">
                                        <?php if ($message['message_type'] === 'received'): ?>
                                        <?php echo htmlspecialchars($message['first_name'] . ' ' . $message['last_name']); ?>
                                        <?php if ($message['status'] === 'unread'): ?>
                                        <span class="badge bg-danger ms-1">New</span>
                                        <?php endif; ?>
                                        <?php else: ?>
                                        To:
                                        <?php echo htmlspecialchars($message['first_name'] . ' ' . $message['last_name']); ?>
                                        <?php endif; ?>
                                    </h6>
                                    <p class="mb-1 fw-bold"><?php echo htmlspecialchars($message['subject']); ?></p>
                                    <p class="mb-1 text-muted">
                                        <?php echo substr(strip_tags($message['message']), 0, 150); ?>...</p>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i>
                                        <?php echo date('M d, Y H:i', strtotime($message['sent_at'])); ?>
                                    </small>
                                </div>
                            </div>

                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#messageModal<?php echo $message['message_id']; ?>"
                                    data-bs-toggle="tooltip" title="View Message">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                <?php if ($message['message_type'] === 'received' && $message['status'] === 'unread'): ?>
                                <a href="?action=mark_read&id=<?php echo $message['message_id']; ?>"
                                    class="btn btn-outline-success" data-bs-toggle="tooltip" title="Mark as Read">
                                    <i class="bi bi-check-lg"></i> Read
                                </a>
                                <?php endif; ?>
                                <a href="?action=delete&id=<?php echo $message['message_id']; ?>&confirm=1"
                                    class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Delete Message"
                                    onclick="return confirm('Are you sure you want to delete this message?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Message Modal -->
                        <div class="modal fade" id="messageModal<?php echo $message['message_id']; ?>" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Message Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="message-header bg-light p-3 rounded mb-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Subject:</strong>
                                                        <?php echo htmlspecialchars($message['subject']); ?></p>
                                                    <p class="mb-1">
                                                        <strong><?php echo $message['message_type'] === 'received' ? 'From:' : 'To:'; ?></strong>
                                                        <?php echo htmlspecialchars($message['first_name'] . ' ' . $message['last_name']); ?>
                                                    </p>
                                                    <p class="mb-0"><strong>Email:</strong>
                                                        <?php echo htmlspecialchars($message['email']); ?></p>
                                                </div>
                                                <div class="col-md-6 text-end">
                                                    <p class="mb-1"><strong>Sent:</strong>
                                                        <?php echo date('M d, Y H:i', strtotime($message['sent_at'])); ?>
                                                    </p>
                                                    <p class="mb-0">
                                                        <strong>Status:</strong>
                                                        <span
                                                            class="badge bg-<?php echo $message['status'] === 'read' ? 'success' : 'warning'; ?>">
                                                            <?php echo ucfirst($message['status']); ?>
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="message-content p-3 border rounded">
                                            <h6>Message:</h6>
                                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($message['message'])); ?>
                                            </p>
                                        </div>

                                        <?php if ($message['message_type'] === 'received'): ?>
                                        <div class="mt-4">
                                            <h6>Reply to this message:</h6>
                                            <form method="POST">
                                                <input type="hidden" name="original_message_id"
                                                    value="<?php echo $message['message_id']; ?>">
                                                <input type="hidden" name="original_subject"
                                                    value="<?php echo htmlspecialchars($message['subject']); ?>">
                                                <input type="hidden" name="sender_id"
                                                    value="<?php echo $message['sender_id']; ?>">

                                                <div class="mb-3">
                                                    <label for="reply_message" class="form-label">Your Reply</label>
                                                    <textarea class="form-control" id="reply_message"
                                                        name="reply_message" rows="5"
                                                        placeholder="Type your reply here..." required></textarea>
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <button type="submit" name="send_reply" class="btn btn-primary">
                                                        <i class="bi bi-send"></i> Send Reply
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if (empty($messages)): ?>
                <div class="text-center py-5">
                    <i class="bi bi-envelope display-1 text-muted"></i>
                    <h4 class="text-muted mt-3">No messages found</h4>
                    <p class="text-muted">
                        <?php if ($type_filter === 'sent'): ?>
                        You haven't sent any messages yet.
                        <?php else: ?>
                        Your inbox is empty.
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>