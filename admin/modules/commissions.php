<?php
$page_title = "Commissions Management";
require_once '../includes/header.php';

// Handle commission actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $commission_id = intval($_GET['id']);
    
    if ($_GET['action'] === 'mark_paid') {
        $stmt = $pdo->prepare("UPDATE commissions SET status = 'paid' WHERE commission_id = ?");
        $stmt->execute([$commission_id]);
        
        log_admin_action("Commission marked as paid", "Commission ID: $commission_id");
        header('Location: commissions.php?success=Commission marked as paid');
        exit();
    }
}

// Handle bulk commission payout
if (isset($_POST['bulk_payout']) && isset($_POST['selected_commissions'])) {
    $commission_ids = $_POST['selected_commissions'];
    $placeholders = str_repeat('?,', count($commission_ids) - 1) . '?';
    
    $stmt = $pdo->prepare("UPDATE commissions SET status = 'paid' WHERE commission_id IN ($placeholders)");
    $stmt->execute($commission_ids);
    
    log_admin_action("Bulk commission payout", count($commission_ids) . " commissions paid");
    header('Location: commissions.php?success=Bulk payout completed');
    exit();
}

// Fetch commissions with filters
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';
$vendor_filter = $_GET['vendor'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

$query = "
    SELECT c.*, o.order_id, o.total_amount, v.first_name, v.last_name, v.username, vv.business_name,
           (c.amount - c.platform_cut) as vendor_earnings
    FROM commissions c 
    JOIN orders o ON c.order_id = o.order_id 
    JOIN users v ON c.vendor_id = v.user_id 
    LEFT JOIN vendor_verification vv ON v.user_id = vv.user_id 
    WHERE 1=1
";

$params = [];
if (!empty($search)) {
    $query .= " AND (vv.business_name LIKE ? OR v.first_name LIKE ? OR v.last_name LIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term, $search_term]);
}

if (!empty($status_filter)) {
    $query .= " AND c.status = ?";
    $params[] = $status_filter;
}

if (!empty($vendor_filter)) {
    $query .= " AND c.vendor_id = ?";
    $params[] = $vendor_filter;
}

if (!empty($date_from)) {
    $query .= " AND DATE(c.created_at) >= ?";
    $params[] = $date_from;
}

if (!empty($date_to)) {
    $query .= " AND DATE(c.created_at) <= ?";
    $params[] = $date_to;
}

$query .= " ORDER BY c.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$commissions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch vendors for filter
$stmt = $pdo->query("SELECT user_id, first_name, last_name, username FROM users WHERE role = 'vendor' ORDER BY first_name");
$vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistics
$total_commissions = count($commissions);
$pending_commissions = array_filter($commissions, fn($c) => $c['status'] === 'pending');
$paid_commissions = array_filter($commissions, fn($c) => $c['status'] === 'paid');
$total_platform_earnings = array_sum(array_column($commissions, 'platform_cut'));
$total_vendor_earnings = array_sum(array_column($commissions, 'vendor_earnings'));

// Vendor earnings summary
$vendor_earnings = [];
foreach ($commissions as $commission) {
    $vendor_id = $commission['vendor_id'];
    if (!isset($vendor_earnings[$vendor_id])) {
        $vendor_earnings[$vendor_id] = [
            'name' => $commission['first_name'] . ' ' . $commission['last_name'],
            'business' => $commission['business_name'],
            'total_earnings' => 0,
            'pending_earnings' => 0
        ];
    }
    $vendor_earnings[$vendor_id]['total_earnings'] += $commission['vendor_earnings'];
    if ($commission['status'] === 'pending') {
        $vendor_earnings[$vendor_id]['pending_earnings'] += $commission['vendor_earnings'];
    }
}
?>

<!-- Statistics Cards -->
<div class="row mb-4 animate-fade-in-up">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo $total_commissions; ?></div>
                    <div class="stat-label">Total Commissions</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-graph-up"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo count($pending_commissions); ?></div>
                    <div class="stat-label">Pending Payout</div>
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
                    <div class="stat-value">$<?php echo number_format($total_platform_earnings, 2); ?></div>
                    <div class="stat-label">Platform Earnings</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value">$<?php echo number_format($total_vendor_earnings, 2); ?></div>
                    <div class="stat-label">Vendor Earnings</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Earnings Summary -->
<div class="card mb-4 animate-slide-in-left">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-bar-chart me-2"></i>Vendor Earnings Summary
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Business</th>
                        <th>Total Earnings</th>
                        <th>Pending Payout</th>
                        <th>Paid Out</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendor_earnings as $vendor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($vendor['name']); ?></td>
                        <td><?php echo htmlspecialchars($vendor['business'] ?? 'N/A'); ?></td>
                        <td class="text-success fw-bold">$<?php echo number_format($vendor['total_earnings'], 2); ?>
                        </td>
                        <td class="text-warning fw-bold">$<?php echo number_format($vendor['pending_earnings'], 2); ?>
                        </td>
                        <td class="text-info fw-bold">
                            $<?php echo number_format($vendor['total_earnings'] - $vendor['pending_earnings'], 2); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Filters and Bulk Actions -->
<div class="card mb-4 animate-slide-in-left">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="bi bi-filter me-2"></i>Filters & Bulk Actions
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Search vendors..."
                    value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending
                    </option>
                    <option value="paid" <?php echo $status_filter === 'paid' ? 'selected' : ''; ?>>Paid</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="vendor" class="form-select">
                    <option value="">All Vendors</option>
                    <?php foreach ($vendors as $vendor): ?>
                    <option value="<?php echo $vendor['user_id']; ?>"
                        <?php echo $vendor_filter == $vendor['user_id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($vendor['first_name'] . ' ' . $vendor['last_name']); ?>
                    </option>
                    <?php endforeach; ?>
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
                <a href="commissions.php" class="btn btn-secondary w-100 mt-2">Reset</a>
            </div>
        </form>

        <!-- Bulk Payout -->
        <?php if (!empty($pending_commissions)): ?>
        <form method="POST" class="mt-3">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <button type="submit" name="bulk_payout" class="btn btn-success"
                        onclick="return confirm('Process payout for all selected commissions?')">
                        <i class="bi bi-cash-coin"></i> Process Bulk Payout
                    </button>
                </div>
                <div class="col-md-8">
                    <small class="text-muted">
                        This will mark all pending commissions as paid. Make sure to process actual payments through
                        your payment system first.
                    </small>
                </div>
            </div>
        </form>
        <?php endif; ?>
    </div>
</div>

<!-- Commissions Table -->
<div class="card animate-slide-in-left">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-graph-up me-2"></i>All Commissions (<?php echo $total_commissions; ?>)
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
                        <th width="30">
                            <input type="checkbox" id="selectAll">
                        </th>
                        <th>Vendor</th>
                        <th>Order</th>
                        <th>Total Amount</th>
                        <th>Platform Cut</th>
                        <th>Vendor Earnings</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($commissions as $commission): ?>
                    <tr>
                        <td>
                            <?php if ($commission['status'] === 'pending'): ?>
                            <input type="checkbox" name="selected_commissions[]"
                                value="<?php echo $commission['commission_id']; ?>" class="commission-checkbox">
                            <?php endif; ?>
                        </td>
                        <td>
                            <div>
                                <strong><?php echo htmlspecialchars($commission['first_name'] . ' ' . $commission['last_name']); ?></strong>
                                <br><small
                                    class="text-muted">@<?php echo htmlspecialchars($commission['username']); ?></small>
                                <?php if ($commission['business_name']): ?>
                                <br><small
                                    class="text-muted"><?php echo htmlspecialchars($commission['business_name']); ?></small>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <a href="orders.php?search=<?php echo $commission['order_id']; ?>"
                                class="text-decoration-none">
                                <strong>#<?php echo $commission['order_id']; ?></strong>
                            </a>
                        </td>
                        <td>
                            <span
                                class="fw-bold text-success">$<?php echo number_format($commission['amount'], 2); ?></span>
                        </td>
                        <td>
                            <span
                                class="fw-bold text-primary">$<?php echo number_format($commission['platform_cut'], 2); ?></span>
                            <br><small
                                class="text-muted"><?php echo number_format(($commission['platform_cut'] / $commission['amount']) * 100, 1); ?>%</small>
                        </td>
                        <td>
                            <span
                                class="fw-bold text-info">$<?php echo number_format($commission['vendor_earnings'], 2); ?></span>
                            <br><small
                                class="text-muted"><?php echo number_format(($commission['vendor_earnings'] / $commission['amount']) * 100, 1); ?>%</small>
                        </td>
                        <td>
                            <span
                                class="badge bg-<?php echo $commission['status'] === 'paid' ? 'success' : 'warning'; ?>">
                                <?php echo ucfirst($commission['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($commission['created_at'])); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#commissionDetailsModal<?php echo $commission['commission_id']; ?>"
                                    data-bs-toggle="tooltip" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <?php if ($commission['status'] === 'pending'): ?>
                                <a href="?action=mark_paid&id=<?php echo $commission['commission_id']; ?>"
                                    class="btn btn-outline-success" data-bs-toggle="tooltip" title="Mark as Paid"
                                    onclick="return confirm('Mark this commission as paid?')">
                                    <i class="bi bi-check-lg"></i>
                                </a>
                                <?php endif; ?>
                            </div>

                            <!-- Commission Details Modal -->
                            <div class="modal fade"
                                id="commissionDetailsModal<?php echo $commission['commission_id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Commission Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h6>Commission Breakdown</h6>
                                                    <p><strong>Vendor:</strong>
                                                        <?php echo htmlspecialchars($commission['first_name'] . ' ' . $commission['last_name']); ?>
                                                    </p>
                                                    <p><strong>Business:</strong>
                                                        <?php echo htmlspecialchars($commission['business_name'] ?? 'N/A'); ?>
                                                    </p>
                                                    <p><strong>Order ID:</strong>
                                                        #<?php echo $commission['order_id']; ?></p>
                                                    <p><strong>Total Order Amount:</strong>
                                                        $<?php echo number_format($commission['total_amount'], 2); ?>
                                                    </p>
                                                    <hr>
                                                    <p><strong>Commission Amount:</strong>
                                                        $<?php echo number_format($commission['amount'], 2); ?></p>
                                                    <p><strong>Platform Cut:</strong>
                                                        $<?php echo number_format($commission['platform_cut'], 2); ?>
                                                        (<?php echo number_format(($commission['platform_cut'] / $commission['amount']) * 100, 1); ?>%)
                                                    </p>
                                                    <p><strong>Vendor Earnings:</strong>
                                                        $<?php echo number_format($commission['vendor_earnings'], 2); ?>
                                                        (<?php echo number_format(($commission['vendor_earnings'] / $commission['amount']) * 100, 1); ?>%)
                                                    </p>
                                                    <p><strong>Status:</strong>
                                                        <span
                                                            class="badge bg-<?php echo $commission['status'] === 'paid' ? 'success' : 'warning'; ?>">
                                                            <?php echo ucfirst($commission['status']); ?>
                                                        </span>
                                                    </p>
                                                    <p><strong>Created:</strong>
                                                        <?php echo date('M d, Y H:i', strtotime($commission['created_at'])); ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <?php if ($commission['status'] === 'pending'): ?>
                                            <a href="?action=mark_paid&id=<?php echo $commission['commission_id']; ?>"
                                                class="btn btn-success"
                                                onclick="return confirm('Mark this commission as paid?')">
                                                <i class="bi bi-check-lg"></i> Mark as Paid
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

        <?php if (empty($commissions)): ?>
        <div class="text-center py-5">
            <i class="bi bi-graph-up display-1 text-muted"></i>
            <h4 class="text-muted mt-3">No commissions found</h4>
            <p class="text-muted">No commissions match your current filters.</p>
            <a href="commissions.php" class="btn btn-primary mt-2">View All Commissions</a>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Bulk actions functionality
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.commission-checkbox');

    // Select all checkboxes
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});
</script>

<?php require_once '../includes/footer.php'; ?>