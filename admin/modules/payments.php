<?php
$page_title = "Payments Management";
require_once '../includes/header.php';

// Handle payment actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $payment_id = intval($_GET['id']);
    
    if ($_GET['action'] === 'update_status') {
        $new_status = $_GET['status'] ?? '';
        if (in_array($new_status, ['pending', 'completed', 'failed'])) {
            $stmt = $pdo->prepare("UPDATE payments SET status = ? WHERE payment_id = ?");
            $stmt->execute([$new_status, $payment_id]);
            
            // Update order payment status if payment is completed
            if ($new_status === 'completed') {
                $stmt = $pdo->prepare("UPDATE orders SET payment_status = 'paid' WHERE order_id = (SELECT order_id FROM payments WHERE payment_id = ?)");
                $stmt->execute([$payment_id]);
            }
            
            log_admin_action("Payment status updated", "Payment ID: $payment_id, Status: $new_status");
            header('Location: payments.php?success=Payment status updated');
            exit();
        }
    }
}

// Fetch payments with filters
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';
$method_filter = $_GET['method'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

$query = "
    SELECT p.*, o.order_id, o.total_amount, u.first_name, u.last_name, u.email,
           (SELECT COUNT(*) FROM order_items WHERE order_id = o.order_id) as item_count
    FROM payments p 
    JOIN orders o ON p.order_id = o.order_id 
    JOIN users u ON p.user_id = u.user_id 
    WHERE 1=1
";

$params = [];
if (!empty($search)) {
    $query .= " AND (p.transaction_id LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
}

if (!empty($status_filter)) {
    $query .= " AND p.status = ?";
    $params[] = $status_filter;
}

if (!empty($method_filter)) {
    $query .= " AND p.payment_method = ?";
    $params[] = $method_filter;
}

if (!empty($date_from)) {
    $query .= " AND DATE(p.created_at) >= ?";
    $params[] = $date_from;
}

if (!empty($date_to)) {
    $query .= " AND DATE(p.created_at) <= ?";
    $params[] = $date_to;
}

$query .= " ORDER BY p.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistics
$total_payments = count($payments);
$completed_payments = array_filter($payments, fn($p) => $p['status'] === 'completed');
$pending_payments = array_filter($payments, fn($p) => $p['status'] === 'pending');
$failed_payments = array_filter($payments, fn($p) => $p['status'] === 'failed');
$total_revenue = array_sum(array_column($completed_payments, 'amount'));

// Payment methods statistics
$payment_methods = [];
foreach ($payments as $payment) {
    $method = $payment['payment_method'] ?? 'Unknown';
    if (!isset($payment_methods[$method])) {
        $payment_methods[$method] = 0;
    }
    $payment_methods[$method]++;
}
?>

<!-- Statistics Cards -->
<div class="row mb-4 animate-fade-in-up">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo $total_payments; ?></div>
                    <div class="stat-label">Total Payments</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-credit-card"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo count($completed_payments); ?></div>
                    <div class="stat-label">Completed</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo count($pending_payments); ?></div>
                    <div class="stat-label">Pending</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value">$<?php echo number_format($total_revenue, 2); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Methods Overview -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card animate-slide-in-left">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-pie-chart me-2"></i>Payment Methods Distribution
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <?php foreach ($payment_methods as $method => $count): 
                        $percentage = $total_payments > 0 ? ($count / $total_payments) * 100 : 0;
                        $colors = [
                            'stripe' => 'primary',
                            'paypal' => 'info',
                            'card' => 'success',
                            'bank_transfer' => 'warning',
                            'crypto' => 'dark'
                        ];
                        $color = $colors[strtolower($method)] ?? 'secondary';
                    ?>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <span class="badge bg-<?php echo $color; ?> p-2">
                                    <i
                                        class="bi bi-<?php echo strtolower($method) === 'paypal' ? 'paypal' : 'credit-card'; ?>"></i>
                                </span>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-0"><?php echo ucfirst($method); ?></h6>
                                <small class="text-muted"><?php echo $count; ?> payments
                                    (<?php echo number_format($percentage, 1); ?>%)</small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
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
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search payments..."
                    value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending
                    </option>
                    <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Completed
                    </option>
                    <option value="failed" <?php echo $status_filter === 'failed' ? 'selected' : ''; ?>>Failed</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="method" class="form-select">
                    <option value="">All Methods</option>
                    <option value="stripe" <?php echo $method_filter === 'stripe' ? 'selected' : ''; ?>>Stripe</option>
                    <option value="paypal" <?php echo $method_filter === 'paypal' ? 'selected' : ''; ?>>PayPal</option>
                    <option value="card" <?php echo $method_filter === 'card' ? 'selected' : ''; ?>>Card</option>
                    <option value="bank_transfer" <?php echo $method_filter === 'bank_transfer' ? 'selected' : ''; ?>>
                        Bank Transfer</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="date_from" class="form-control"
                    value="<?php echo htmlspecialchars($date_from); ?>" placeholder="From Date">
            </div>
            <div class="col-md-2">
                <input type="date" name="date_to" class="form-control" value="<?php echo htmlspecialchars($date_to); ?>"
                    placeholder="To Date">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="payments.php" class="btn btn-secondary w-100 mt-2">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Payments Table -->
<div class="card animate-slide-in-left">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-credit-card me-2"></i>All Payments (<?php echo $total_payments; ?>)
        </h5>
        <button type="button" class="btn btn-outline-secondary btn-sm" data-export="csv">
            <i class="bi bi-download me-1"></i> Export
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Customer</th>
                        <th>Order</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                    <tr>
                        <td>
                            <code><?php echo htmlspecialchars($payment['transaction_id'] ?? 'N/A'); ?></code>
                        </td>
                        <td>
                            <div>
                                <strong><?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?></strong>
                                <br><small class="text-muted"><?php echo htmlspecialchars($payment['email']); ?></small>
                            </div>
                        </td>
                        <td>
                            <a href="orders.php?search=<?php echo $payment['order_id']; ?>"
                                class="text-decoration-none">
                                <strong>#<?php echo $payment['order_id']; ?></strong>
                            </a>
                            <br><small class="text-muted"><?php echo $payment['item_count']; ?> items</small>
                        </td>
                        <td>
                            <span
                                class="fw-bold text-success">$<?php echo number_format($payment['amount'], 2); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">
                                <i
                                    class="bi bi-<?php echo strtolower($payment['payment_method']) === 'paypal' ? 'paypal' : 'credit-card'; ?> me-1"></i>
                                <?php echo ucfirst($payment['payment_method']); ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-<?php 
                                echo $payment['status'] === 'completed' ? 'success' : 
                                       ($payment['status'] === 'pending' ? 'warning' : 'danger'); 
                            ?>">
                                <?php echo ucfirst($payment['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y H:i', strtotime($payment['created_at'])); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#paymentDetailsModal<?php echo $payment['payment_id']; ?>"
                                    data-bs-toggle="tooltip" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <?php if ($payment['status'] === 'pending'): ?>
                                <a href="?action=update_status&id=<?php echo $payment['payment_id']; ?>&status=completed"
                                    class="btn btn-outline-success" data-bs-toggle="tooltip" title="Mark as Completed"
                                    onclick="return confirm('Mark this payment as completed?')">
                                    <i class="bi bi-check-lg"></i>
                                </a>
                                <a href="?action=update_status&id=<?php echo $payment['payment_id']; ?>&status=failed"
                                    class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Mark as Failed"
                                    onclick="return confirm('Mark this payment as failed?')">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                                <?php endif; ?>
                            </div>

                            <!-- Payment Details Modal -->
                            <div class="modal fade" id="paymentDetailsModal<?php echo $payment['payment_id']; ?>"
                                tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Payment Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Payment Information</h6>
                                                    <p><strong>Transaction ID:</strong>
                                                        <code><?php echo htmlspecialchars($payment['transaction_id']); ?></code>
                                                    </p>
                                                    <p><strong>Amount:</strong>
                                                        $<?php echo number_format($payment['amount'], 2); ?></p>
                                                    <p><strong>Payment Method:</strong>
                                                        <?php echo ucfirst($payment['payment_method']); ?></p>
                                                    <p><strong>Status:</strong>
                                                        <span class="badge bg-<?php 
                                                            echo $payment['status'] === 'completed' ? 'success' : 
                                                                   ($payment['status'] === 'pending' ? 'warning' : 'danger'); 
                                                        ?>">
                                                            <?php echo ucfirst($payment['status']); ?>
                                                        </span>
                                                    </p>
                                                    <p><strong>Created:</strong>
                                                        <?php echo date('M d, Y H:i', strtotime($payment['created_at'])); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Customer Information</h6>
                                                    <p><strong>Name:</strong>
                                                        <?php echo htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']); ?>
                                                    </p>
                                                    <p><strong>Email:</strong>
                                                        <?php echo htmlspecialchars($payment['email']); ?></p>
                                                    <p><strong>Order ID:</strong> #<?php echo $payment['order_id']; ?>
                                                    </p>
                                                    <p><strong>Order Amount:</strong>
                                                        $<?php echo number_format($payment['total_amount'], 2); ?></p>
                                                    <p><strong>Items:</strong> <?php echo $payment['item_count']; ?>
                                                        items</p>
                                                </div>
                                            </div>

                                            <?php if ($payment['status'] === 'pending'): ?>
                                            <hr>
                                            <div class="alert alert-warning">
                                                <h6><i class="bi bi-exclamation-triangle"></i> Pending Payment</h6>
                                                <p class="mb-0">This payment is still pending. You can manually update
                                                    its status if needed.</p>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="modal-footer">
                                            <?php if ($payment['status'] === 'pending'): ?>
                                            <a href="?action=update_status&id=<?php echo $payment['payment_id']; ?>&status=completed"
                                                class="btn btn-success"
                                                onclick="return confirm('Mark this payment as completed?')">
                                                <i class="bi bi-check-lg"></i> Mark Completed
                                            </a>
                                            <a href="?action=update_status&id=<?php echo $payment['payment_id']; ?>&status=failed"
                                                class="btn btn-danger"
                                                onclick="return confirm('Mark this payment as failed?')">
                                                <i class="bi bi-x-lg"></i> Mark Failed
                                            </a>
                                            <?php endif; ?>
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($payments)): ?>
        <div class="text-center py-5">
            <i class="bi bi-credit-card display-1 text-muted"></i>
            <h4 class="text-muted mt-3">No payments found</h4>
            <p class="text-muted">No payments match your current filters.</p>
            <a href="payments.php" class="btn btn-primary mt-2">View All Payments</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>