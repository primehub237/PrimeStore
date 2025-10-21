<?php
$page_title = "Users Management";
require_once '../includes/header.php';

// Handle user actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $user_id = intval($_GET['id']);
    
    if ($_GET['action'] === 'toggle_status') {
        $stmt = $pdo->prepare("UPDATE users SET status = IF(status = 'active', 'suspended', 'active') WHERE user_id = ?");
        $stmt->execute([$user_id]);
        log_admin_action("User status toggled", "User ID: $user_id");
        header('Location: users.php?success=User status updated');
        exit();
    } elseif ($_GET['action'] === 'delete' && isset($_GET['confirm'])) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ? AND role != 'admin'");
        $stmt->execute([$user_id]);
        log_admin_action("User deleted", "User ID: $user_id");
        header('Location: users.php?success=User deleted successfully');
        exit();
    }
}

// Fetch users
$search = $_GET['search'] ?? '';
$role_filter = $_GET['role'] ?? '';

$query = "SELECT * FROM users WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term, $search_term]);
}

if (!empty($role_filter)) {
    $query .= " AND role = ?";
    $params[] = $role_filter;
}

$query .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistics
$total_users = count($users);
$clients = array_filter($users, fn($u) => $u['role'] === 'client');
$vendors = array_filter($users, fn($u) => $u['role'] === 'vendor');
$admins = array_filter($users, fn($u) => $u['role'] === 'admin');
$active_users = array_filter($users, fn($u) => $u['status'] === 'active');
?>

<!-- Statistics Cards -->
<div class="row mb-4 animate-fade-in-up">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo $total_users; ?></div>
                    <div class="stat-label">Total Users</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo count($clients); ?></div>
                    <div class="stat-label">Clients</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-person"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo count($vendors); ?></div>
                    <div class="stat-label">Vendors</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-shop"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo count($active_users); ?></div>
                    <div class="stat-label">Active Users</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="card mb-4 animate-slide-in-left">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-filter me-2"></i>Filters & Search
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Search users by name or email..."
                    value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-4">
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="client" <?php echo $role_filter === 'client' ? 'selected' : ''; ?>>Clients</option>
                    <option value="vendor" <?php echo $role_filter === 'vendor' ? 'selected' : ''; ?>>Vendors</option>
                    <option value="admin" <?php echo $role_filter === 'admin' ? 'selected' : ''; ?>>Admins</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="users.php" class="btn btn-secondary w-100 mt-2">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Users Table -->
<div class="card animate-slide-in-left">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-list-ul me-2"></i>All Users (<?php echo $total_users; ?>)
        </h5>
        <a href="?export=csv" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-download me-1"></i> Export CSV
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="../uploads/profiles/<?php echo htmlspecialchars($user['profile_img']); ?>"
                                    alt="Profile" class="rounded-circle me-3" width="45" height="45"
                                    style="object-fit: cover;">
                                <div>
                                    <strong><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></strong>
                                    <br><small
                                        class="text-muted">@<?php echo htmlspecialchars($user['username']); ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td>
                            <span class="badge bg-<?php 
                                echo $user['role'] === 'admin' ? 'danger' : 
                                       ($user['role'] === 'vendor' ? 'info' : 'secondary'); 
                            ?>">
                                <i class="bi bi-<?php 
                                    echo $user['role'] === 'admin' ? 'shield-check' : 
                                           ($user['role'] === 'vendor' ? 'shop' : 'person'); 
                                ?> me-1"></i>
                                <?php echo ucfirst($user['role']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $user['status'] === 'active' ? 'success' : 'warning'; ?>">
                                <i
                                    class="bi bi-<?php echo $user['status'] === 'active' ? 'check-circle' : 'pause-circle'; ?> me-1"></i>
                                <?php echo ucfirst($user['status']); ?>
                            </span>
                        </td>
                        <td>
                            <small><?php echo date('M d, Y', strtotime($user['created_at'])); ?></small>
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="user_details.php?id=<?php echo $user['user_id']; ?>"
                                    class="btn btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if ($user['role'] != 'admin'): ?>
                                <a href="?action=toggle_status&id=<?php echo $user['user_id']; ?>"
                                    class="btn btn-outline-<?php echo $user['status'] === 'active' ? 'warning' : 'success'; ?>"
                                    data-bs-toggle="tooltip"
                                    title="<?php echo $user['status'] === 'active' ? 'Suspend' : 'Activate'; ?>"
                                    onclick="return confirm('Are you sure?')">
                                    <i class="bi bi-<?php echo $user['status'] === 'active' ? 'pause' : 'play'; ?>"></i>
                                </a>
                                <a href="?action=delete&id=<?php echo $user['user_id']; ?>&confirm=1"
                                    class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Delete User"
                                    onclick="return confirm('Are you sure you want to delete this user?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($users)): ?>
        <div class="text-center py-5">
            <i class="bi bi-people display-1 text-muted"></i>
            <h4 class="text-muted mt-3">No users found</h4>
            <p class="text-muted">Try adjusting your search filters.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>