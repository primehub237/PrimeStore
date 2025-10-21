<?php
$page_title = "Orders Management";
require_once '../includes/header.php';

// Handle order status update
if (isset($_POST['update_status']) && isset($_POST['order_id']) && isset($_POST['order_status'])) {
    $order_id = intval($_POST['order_id']);
    $order_status = $_POST['order_status'];
    
    $stmt = $pdo->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt->execute([$order_status, $order_id]);
    
    // Log the status change
    log_admin_action("Order status updated", "Order ID: $order_id, Status: $order_status");
    
    // If order is delivered, update payment status if not already paid
    if ($order_status === 'delivered') {
        $stmt = $pdo->prepare("UPDATE orders SET payment_status = 'paid' WHERE order_id = ? AND payment_status = 'pending'");
        $stmt->execute([$order_id]);
    }
    
    header('Location: orders.php?success=Order status updated');
    exit();
}

// Handle payment status update
if (isset($_POST['update_payment_status']) && isset($_POST['order_id']) && isset($_POST['payment_status'])) {
    $order_id = intval($_POST['order_id']);
    $payment_status = $_POST['payment_status'];
    
    $stmt = $pdo->prepare("UPDATE orders SET payment_status = ? WHERE order_id = ?");
    $stmt->execute([$payment_status, $order_id]);
    
    log_admin_action("Payment status updated", "Order ID: $order_id, Payment Status: $payment_status");
    header('Location: orders.php?success=Payment status updated');
    exit();
}

// Fetch orders with filters
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';
$payment_filter = $_GET['payment'] ?? '';

$query = "
    SELECT o.*, u.first_name, u.last_name, u.email, u.phone,
           (SELECT COUNT(*) FROM order_items WHERE order_id = o.order_id) as item_count,
           (SELECT SUM(quantity) FROM order_items WHERE order_id = o.order_id) as total_quantity
    FROM orders o 
    JOIN users u ON o.user_id = u.user_id 
    WHERE 1=1
";

$params = [];
if (!empty($search)) {
    $query .= " AND (o.order_id LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
}

if (!empty($status_filter)) {
    $query .= " AND o.order_status = ?";
    $params[] = $status_filter;
}

if (!empty($payment_filter)) {
    $query .= " AND o.payment_status = ?";
    $params[] = $payment_filter;
}

$query .= " ORDER BY o.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistics
$total_orders = count($orders);
$pending_orders = array_filter($orders, fn($o) => $o['order_status'] === 'pending');
$processing_orders = array_filter($orders, fn($o) => $o['order_status'] === 'processing');
$shipped_orders = array_filter($orders, fn($o) => $o['order_status'] === 'shipped');
$delivered_orders = array_filter($orders, fn($o) => $o['order_status'] === 'delivered');
$paid_orders = array_filter($orders, fn($o) => $o['payment_status'] === 'paid');
$total_revenue = array_sum(array_column($paid_orders, 'total_amount'));
?>

<!-- Statistics Cards -->
<div class="row mb-4 animate-fade-in-up">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo $total_orders; ?></div>
                    <div class="stat-label">Total Orders</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-cart"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo count($pending_orders); ?></div>
                    <div class="stat-label">Pending Orders</div>
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
                    <div class="stat-value"><?php echo count($paid_orders); ?></div>
                    <div class="stat-label">Paid Orders</div>
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
                <input type="text" name="search" class="form-control" placeholder="Search orders..."
                    value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Order Status</option>
                    <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending
                    </option>
                    <option value="processing" <?php echo $status_filter === 'processing' ? 'selected' : ''; ?>>
                        Processing</option>
                    <option value="shipped" <?php echo $status_filter === 'shipped' ? 'selected' : ''; ?>>Shipped
                    </option>
                    <option value="delivered" <?php echo $status_filter === 'delivered' ? 'selected' : ''; ?>>Delivered
                    </option>
                    <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="payment" class="form-select">
                    <option value="">All Payment Status</option>
                    <option value="pending" <?php echo $payment_filter === 'pending' ? 'selected' : ''; ?>>Pending
                    </option>
                    <option value="paid" <?php echo $payment_filter === 'paid' ? 'selected' : ''; ?>>Paid</option>
                    <option value="failed" <?php echo $payment_filter === 'failed' ? 'selected' : ''; ?>>Failed</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
                <a href="orders.php" class="btn btn-secondary w-100 mt-2">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="card animate-slide-in-left">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-cart me-2"></i>All Orders (<?php echo $total_orders; ?>)
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
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Shipping Address</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>
                            <strong>#<?php echo $order['order_id']; ?></strong>
                        </td>
                        <td>
                            <div>
                                <strong><?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?></strong>
                                <br><small class="text-muted"><?php echo htmlspecialchars($order['email']); ?></small>
                                <br><small class="text-muted"><?php echo htmlspecialchars($order['phone']); ?></small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-primary"><?php echo $order['item_count']; ?> items</span>
                            <br><small class="text-muted"><?php echo $order['total_quantity']; ?> total qty</small>
                        </td>
                        <td>
                            <span
                                class="fw-bold text-success">$<?php echo number_format($order['total_amount'], 2); ?></span>
                        </td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <select name="payment_status" class="form-select form-select-sm"
                                    onchange="this.form.submit()"
                                    style="background: <?php echo $order['payment_status'] === 'paid' ? '#d4edda' : ($order['payment_status'] === 'failed' ? '#f8d7da' : '#fff3cd'); ?>">
                                    <option value="pending"
                                        <?php echo $order['payment_status'] === 'pending' ? 'selected' : ''; ?>>Pending
                                    </option>
                                    <option value="paid"
                                        <?php echo $order['payment_status'] === 'paid' ? 'selected' : ''; ?>>Paid
                                    </option>
                                    <option value="failed"
                                        <?php echo $order['payment_status'] === 'failed' ? 'selected' : ''; ?>>Failed
                                    </option>
                                </select>
                                <input type="hidden" name="update_payment_status" value="1">
                            </form>
                        </td>
                        <td>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                                <select name="order_status" class="form-select form-select-sm"
                                    onchange="this.form.submit()"
                                    style="background: <?php 
                                        echo $order['order_status'] === 'delivered' ? '#d4edda' : 
                                               ($order['order_status'] === 'cancelled' ? '#f8d7da' : 
                                               ($order['order_status'] === 'shipped' ? '#cce7ff' : 
                                               ($order['order_status'] === 'processing' ? '#fff3cd' : '#e2e3e5'))); ?>">
                                    <option value="pending"
                                        <?php echo $order['order_status'] === 'pending' ? 'selected' : ''; ?>>Pending
                                    </option>
                                    <option value="processing"
                                        <?php echo $order['order_status'] === 'processing' ? 'selected' : ''; ?>>
                                        Processing</option>
                                    <option value="shipped"
                                        <?php echo $order['order_status'] === 'shipped' ? 'selected' : ''; ?>>Shipped
                                    </option>
                                    <option value="delivered"
                                        <?php echo $order['order_status'] === 'delivered' ? 'selected' : ''; ?>>
                                        Delivered</option>
                                    <option value="cancelled"
                                        <?php echo $order['order_status'] === 'cancelled' ? 'selected' : ''; ?>>
                                        Cancelled</option>
                                </select>
                                <input type="hidden" name="update_status" value="1">
                            </form>
                        </td>
                        <td>
                            <small
                                class="text-muted"><?php echo htmlspecialchars($order['shipping_address'] ?? 'Not provided'); ?></small>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#orderDetailsModal<?php echo $order['order_id']; ?>"
                                    data-bs-toggle="tooltip" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="order_invoice.php?id=<?php echo $order['order_id']; ?>"
                                    class="btn btn-outline-info" target="_blank" data-bs-toggle="tooltip"
                                    title="View Invoice">
                                    <i class="bi bi-receipt"></i>
                                </a>
                            </div>

                            <!-- Order Details Modal -->
                            <div class="modal fade" id="orderDetailsModal<?php echo $order['order_id']; ?>"
                                tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Order Details - #<?php echo $order['order_id']; ?>
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                            // Fetch order items
                                            $stmt = $pdo->prepare("
                                                SELECT oi.*, p.product_name, p.image, u.first_name as vendor_first, u.last_name as vendor_last
                                                FROM order_items oi
                                                JOIN products p ON oi.product_id = p.product_id
                                                LEFT JOIN users u ON oi.vendor_id = u.user_id
                                                WHERE oi.order_id = ?
                                            ");
                                            $stmt->execute([$order['order_id']]);
                                            $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            ?>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Customer Information</h6>
                                                    <p><strong>Name:</strong>
                                                        <?php echo htmlspecialchars($order['first_name'] . ' ' . $order['last_name']); ?>
                                                    </p>
                                                    <p><strong>Email:</strong>
                                                        <?php echo htmlspecialchars($order['email']); ?></p>
                                                    <p><strong>Phone:</strong>
                                                        <?php echo htmlspecialchars($order['phone']); ?></p>
                                                    <p><strong>Shipping Address:</strong>
                                                        <?php echo htmlspecialchars($order['shipping_address']); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Order Information</h6>
                                                    <p><strong>Order Date:</strong>
                                                        <?php echo date('M d, Y H:i', strtotime($order['created_at'])); ?>
                                                    </p>
                                                    <p><strong>Total Amount:</strong>
                                                        $<?php echo number_format($order['total_amount'], 2); ?></p>
                                                    <p><strong>Payment Status:</strong>
                                                        <span
                                                            class="badge bg-<?php echo $order['payment_status'] === 'paid' ? 'success' : ($order['payment_status'] === 'pending' ? 'warning' : 'danger'); ?>">
                                                            <?php echo ucfirst($order['payment_status']); ?>
                                                        </span>
                                                    </p>
                                                    <p><strong>Order Status:</strong>
                                                        <span
                                                            class="badge bg-<?php 
                                                        echo $order['order_status'] === 'delivered' ? 'success' : 
                                                               ($order['order_status'] === 'cancelled' ? 'danger' : 
                                                               ($order['order_status'] === 'shipped' ? 'primary' : 
                                                               ($order['order_status'] === 'processing' ? 'info' : 'warning'))); ?>">
                                                            <?php echo ucfirst($order['order_status']); ?>
                                                        </span>
                                                    </p>
                                                </div>
                                            </div>

                                            <hr>

                                            <h6>Order Items</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Vendor</th>
                                                            <th>Quantity</th>
                                                            <th>Price</th>
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($order_items as $item): ?>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <img src="../uploads/products/<?php echo htmlspecialchars($item['image'] ?? 'default.png'); ?>"
                                                                        alt="Product" class="rounded me-2" width="40"
                                                                        height="40" style="object-fit: cover;">
                                                                    <div>
                                                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <?php if ($item['vendor_first']): ?>
                                                                <?php echo htmlspecialchars($item['vendor_first'] . ' ' . $item['vendor_last']); ?>
                                                                <?php else: ?>
                                                                <span class="text-muted">Platform</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo $item['quantity']; ?></td>
                                                            <td>$<?php echo number_format($item['price'], 2); ?></td>
                                                            <td>$<?php echo number_format($item['total'], 2); ?></td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="4" class="text-end"><strong>Total
                                                                    Amount:</strong></td>
                                                            <td><strong>$<?php echo number_format($order['total_amount'], 2); ?></strong>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
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

        <?php if (empty($orders)): ?>
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h4 class="text-muted mt-3">No orders found</h4>
            <p class="text-muted">No orders match your current filters.</p>
            <a href="orders.php" class="btn btn-primary mt-2">View All Orders</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>