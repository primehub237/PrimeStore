<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_admin_login();

$page_title = "Dashboard";
?>
<?php require_once 'includes/header.php'; ?>

<!-- Dashboard Content -->
<?php
// Get dashboard statistics
$stats = [];
try {
    // Total Users
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'client'");
    $stats['total_clients'] = $stmt->fetch()['total'];
    
    // Total Vendors
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users WHERE role = 'vendor'");
    $stats['total_vendors'] = $stmt->fetch()['total'];
    
    // Total Products
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM products");
    $stats['total_products'] = $stmt->fetch()['total'];
    
    // Total Orders
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM orders");
    $stats['total_orders'] = $stmt->fetch()['total'];
    
    // Pending Orders
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM orders WHERE order_status = 'pending'");
    $stats['pending_orders'] = $stmt->fetch()['total'];
    
    // Total Revenue
    $stmt = $pdo->query("SELECT SUM(total_amount) as total FROM orders WHERE payment_status = 'paid'");
    $stats['total_revenue'] = $stmt->fetch()['total'] ?? 0;
    
    // Pending Vendor Verifications
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM vendor_verification WHERE status = 'pending'");
    $stats['pending_verifications'] = $stmt->fetch()['total'];
    
} catch (PDOException $e) {
    error_log("Dashboard stats error: " . $e->getMessage());
}
?>

<div class="row animate-fade-in-up dashboard-stats">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo $stats['total_clients']; ?></div>
                    <div class="stat-label">Total Clients</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo $stats['total_vendors']; ?></div>
                    <div class="stat-label">Total Vendors</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-shop"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo $stats['total_products']; ?></div>
                    <div class="stat-label">Total Products</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-box"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value">$<?php echo number_format($stats['total_revenue'], 2); ?></div>
                    <div class="stat-label">Total Revenue</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Recent Orders -->
    <div class="col-lg-8">
        <div class="card animate-slide-in-left">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-cart me-2"></i>Recent Orders
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->query("
                                SELECT o.order_id, o.total_amount, o.order_status, o.created_at, 
                                       u.first_name, u.last_name 
                                FROM orders o 
                                JOIN users u ON o.user_id = u.user_id 
                                ORDER BY o.created_at DESC 
                                LIMIT 5
                            ");
                            while ($order = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $status_class = [
                                    'pending' => 'warning',
                                    'processing' => 'info',
                                    'shipped' => 'primary',
                                    'delivered' => 'success',
                                    'cancelled' => 'danger'
                                ][$order['order_status']] ?? 'secondary';
                                
                                echo "
                                <tr>
                                    <td><strong>#{$order['order_id']}</strong></td>
                                    <td>{$order['first_name']} {$order['last_name']}</td>
                                    <td>$" . number_format($order['total_amount'], 2) . "</td>
                                    <td><span class='badge bg-$status_class'>{$order['order_status']}</span></td>
                                    <td>" . date('M d, Y', strtotime($order['created_at'])) . "</td>
                                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- System Status & Quick Actions -->
    <div class="col-lg-4">
        <!-- System Status -->
        <div class="card mb-4 animate-slide-in-left">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-heart-pulse me-2"></i>System Status
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 border-bottom">
                    <span class="fw-bold">Pending Orders:</span>
                    <span class="badge bg-warning"><?php echo $stats['pending_orders']; ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 border-bottom">
                    <span class="fw-bold">Vendor Verifications:</span>
                    <span class="badge bg-info"><?php echo $stats['pending_verifications']; ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3 p-2 border-bottom">
                    <span class="fw-bold">AliExpress Sync:</span>
                    <span class="badge bg-success">Active</span>
                </div>
                <div class="d-flex justify-content-between align-items-center p-2">
                    <span class="fw-bold">Server Load:</span>
                    <span class="badge bg-success">Normal</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card animate-slide-in-left">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="modules/products.php?action=add" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Add Product
                    </a>
                    <a href="modules/vendors.php" class="btn btn-info">
                        <i class="bi bi-shop me-2"></i>Manage Vendors
                    </a>
                    <a href="modules/orders.php" class="btn btn-warning">
                        <i class="bi bi-cart me-2"></i>View Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>