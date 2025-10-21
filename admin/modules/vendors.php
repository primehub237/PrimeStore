<?php
$page_title = "Vendors Management";
require_once '../includes/header.php';

// Handle vendor actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $vendor_id = intval($_GET['id']);
    
    if ($_GET['action'] === 'approve_verification') {
        $stmt = $pdo->prepare("UPDATE vendor_verification SET status = 'approved' WHERE verify_id = ?");
        $stmt->execute([$vendor_id]);
        
        // Update user role to vendor
        $stmt = $pdo->prepare("UPDATE users SET role = 'vendor' WHERE user_id = (SELECT user_id FROM vendor_verification WHERE verify_id = ?)");
        $stmt->execute([$vendor_id]);
        
        log_admin_action("Vendor verification approved", "Verification ID: $vendor_id");
        header('Location: vendors.php?success=Vendor approved successfully');
        exit();
    } elseif ($_GET['action'] === 'reject_verification') {
        $stmt = $pdo->prepare("UPDATE vendor_verification SET status = 'rejected' WHERE verify_id = ?");
        $stmt->execute([$vendor_id]);
        log_admin_action("Vendor verification rejected", "Verification ID: $vendor_id");
        header('Location: vendors.php?success=Vendor application rejected');
        exit();
    } elseif ($_GET['action'] === 'toggle_status') {
        $stmt = $pdo->prepare("UPDATE users SET status = IF(status = 'active', 'suspended', 'active') WHERE user_id = ? AND role = 'vendor'");
        $stmt->execute([$vendor_id]);
        log_admin_action("Vendor status toggled", "Vendor ID: $vendor_id");
        header('Location: vendors.php?success=Vendor status updated');
        exit();
    }
}

// Handle withdrawal actions
if (isset($_GET['withdraw_action']) && isset($_GET['withdraw_id'])) {
    $withdraw_id = intval($_GET['withdraw_id']);
    
    if ($_GET['withdraw_action'] === 'approve') {
        $stmt = $pdo->prepare("UPDATE vendor_withdrawals SET status = 'approved' WHERE withdraw_id = ?");
        $stmt->execute([$withdraw_id]);
        log_admin_action("Withdrawal approved", "Withdrawal ID: $withdraw_id");
        header('Location: vendors.php?success=Withdrawal approved');
        exit();
    } elseif ($_GET['withdraw_action'] === 'reject') {
        $stmt = $pdo->prepare("UPDATE vendor_withdrawals SET status = 'rejected' WHERE withdraw_id = ?");
        $stmt->execute([$withdraw_id]);
        log_admin_action("Withdrawal rejected", "Withdrawal ID: $withdraw_id");
        header('Location: vendors.php?success=Withdrawal rejected');
        exit();
    }
}

// Fetch pending verifications
$stmt = $pdo->query("
    SELECT vv.*, u.first_name, u.last_name, u.email, u.phone 
    FROM vendor_verification vv 
    JOIN users u ON vv.user_id = u.user_id 
    WHERE vv.status = 'pending'
    ORDER BY vv.submitted_at DESC
");
$pending_verifications = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all vendors
$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

$query = "
    SELECT u.*, vv.business_name, vv.country, vv.status as verification_status,
           (SELECT COUNT(*) FROM products WHERE user_id = u.user_id) as product_count,
           (SELECT SUM(amount) FROM commissions WHERE vendor_id = u.user_id AND status = 'paid') as total_earnings
    FROM users u 
    LEFT JOIN vendor_verification vv ON u.user_id = vv.user_id 
    WHERE u.role = 'vendor'
";

$params = [];
if (!empty($search)) {
    $query .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ? OR vv.business_name LIKE ?)";
    $search_term = "%$search%";
    $params = array_merge($params, [$search_term, $search_term, $search_term, $search_term]);
}

if (!empty($status_filter)) {
    $query .= " AND u.status = ?";
    $params[] = $status_filter;
}

$query .= " ORDER BY u.created_at DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$vendors = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch pending withdrawals
$stmt = $pdo->query("
    SELECT w.*, u.first_name, u.last_name, u.email, vv.business_name
    FROM vendor_withdrawals w
    JOIN users u ON w.vendor_id = u.user_id
    LEFT JOIN vendor_verification vv ON u.user_id = vv.user_id
    WHERE w.status = 'pending'
    ORDER BY w.requested_at DESC
");
$pending_withdrawals = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Statistics
$total_vendors = count($vendors);
$active_vendors = array_filter($vendors, fn($v) => $v['status'] === 'active');
$pending_verifications_count = count($pending_verifications);
$pending_withdrawals_count = count($pending_withdrawals);
$total_vendor_products = array_sum(array_column($vendors, 'product_count'));
?>

<!-- Statistics Cards -->
<div class="row mb-4 animate-fade-in-up">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo $total_vendors; ?></div>
                    <div class="stat-label">Total Vendors</div>
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
                    <div class="stat-value"><?php echo $pending_verifications_count; ?></div>
                    <div class="stat-label">Pending Verifications</div>
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
                    <div class="stat-value"><?php echo $pending_withdrawals_count; ?></div>
                    <div class="stat-label">Pending Withdrawals</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-cash-coin"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                    <div class="stat-value"><?php echo $total_vendor_products; ?></div>
                    <div class="stat-label">Vendor Products</div>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-box"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pending Verifications -->
<?php if (!empty($pending_verifications)): ?>
<div class="card mb-4 animate-slide-in-left">
    <div class="card-header bg-warning text-dark">
        <h5 class="card-title mb-0">
            <i class="bi bi-exclamation-triangle me-2"></i>Pending Vendor Verifications
            (<?php echo $pending_verifications_count; ?>)
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Business Name</th>
                        <th>Applicant</th>
                        <th>Contact</th>
                        <th>Country</th>
                        <th>Submitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_verifications as $verification): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($verification['business_name']); ?></strong>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($verification['first_name'] . ' ' . $verification['last_name']); ?>
                        </td>
                        <td>
                            <div><?php echo htmlspecialchars($verification['email']); ?></div>
                            <small class="text-muted"><?php echo htmlspecialchars($verification['phone']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($verification['country']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($verification['submitted_at'])); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#viewVerificationModal<?php echo $verification['verify_id']; ?>"
                                    data-bs-toggle="tooltip" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="?action=approve_verification&id=<?php echo $verification['verify_id']; ?>"
                                    class="btn btn-outline-success" data-bs-toggle="tooltip" title="Approve Vendor"
                                    onclick="return confirm('Approve this vendor?')">
                                    <i class="bi bi-check-lg"></i>
                                </a>
                                <a href="?action=reject_verification&id=<?php echo $verification['verify_id']; ?>"
                                    class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Reject Application"
                                    onclick="return confirm('Reject this vendor application?')">
                                    <i class="bi bi-x-lg"></i>
                                </a>
                            </div>

                            <!-- View Verification Modal -->
                            <div class="modal fade" id="viewVerificationModal<?php echo $verification['verify_id']; ?>"
                                tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Vendor Verification Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Business Information</h6>
                                                    <p><strong>Business Name:</strong>
                                                        <?php echo htmlspecialchars($verification['business_name']); ?>
                                                    </p>
                                                    <p><strong>Country:</strong>
                                                        <?php echo htmlspecialchars($verification['country']); ?></p>
                                                    <p><strong>Address:</strong>
                                                        <?php echo htmlspecialchars($verification['address']); ?></p>
                                                    <p><strong>ID Card:</strong>
                                                        <?php echo htmlspecialchars($verification['id_card_number']); ?>
                                                    </p>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Applicant Information</h6>
                                                    <p><strong>Name:</strong>
                                                        <?php echo htmlspecialchars($verification['first_name'] . ' ' . $verification['last_name']); ?>
                                                    </p>
                                                    <p><strong>Email:</strong>
                                                        <?php echo htmlspecialchars($verification['email']); ?></p>
                                                    <p><strong>Phone:</strong>
                                                        <?php echo htmlspecialchars($verification['phone']); ?></p>
                                                    <p><strong>Submitted:</strong>
                                                        <?php echo date('M d, Y H:i', strtotime($verification['submitted_at'])); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <?php if ($verification['document_img'] && $verification['document_img'] !== 'default.png'): ?>
                                            <div class="mt-3">
                                                <h6>Document Image</h6>
                                                <img src="../uploads/documents/<?php echo htmlspecialchars($verification['document_img']); ?>"
                                                    alt="Verification Document" class="img-fluid rounded"
                                                    style="max-height: 300px;">
                                            </div>
                                            <?php endif; ?>
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
    </div>
</div>
<?php endif; ?>

<!-- Pending Withdrawals -->
<?php if (!empty($pending_withdrawals)): ?>
<div class="card mb-4 animate-slide-in-left">
    <div class="card-header bg-info text-white">
        <h5 class="card-title mb-0">
            <i class="bi bi-cash-coin me-2"></i>Pending Withdrawals (<?php echo $pending_withdrawals_count; ?>)
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Business</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Account Details</th>
                        <th>Requested</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_withdrawals as $withdrawal): ?>
                    <tr>
                        <td>
                            <strong><?php echo htmlspecialchars($withdrawal['first_name'] . ' ' . $withdrawal['last_name']); ?></strong>
                            <br><small class="text-muted"><?php echo htmlspecialchars($withdrawal['email']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($withdrawal['business_name']); ?></td>
                        <td>
                            <span
                                class="fw-bold text-success">$<?php echo number_format($withdrawal['amount'], 2); ?></span>
                        </td>
                        <td><?php echo htmlspecialchars($withdrawal['payment_method']); ?></td>
                        <td>
                            <small
                                class="text-muted"><?php echo htmlspecialchars($withdrawal['account_details']); ?></small>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($withdrawal['requested_at'])); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="?withdraw_action=approve&withdraw_id=<?php echo $withdrawal['withdraw_id']; ?>"
                                    class="btn btn-outline-success"
                                    onclick="return confirm('Approve this withdrawal?')">
                                    <i class="bi bi-check-lg"></i> Approve
                                </a>
                                <a href="?withdraw_action=reject&withdraw_id=<?php echo $withdrawal['withdraw_id']; ?>"
                                    class="btn btn-outline-danger" onclick="return confirm('Reject this withdrawal?')">
                                    <i class="bi bi-x-lg"></i> Reject
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- All Vendors -->
<div class="card animate-slide-in-left">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <i class="bi bi-list-ul me-2"></i>All Vendors (<?php echo $total_vendors; ?>)
        </h5>
        <div>
            <button type="button" class="btn btn-outline-secondary btn-sm" data-export="csv">
                <i class="bi bi-download me-1"></i> Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Filters -->
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Search vendors..." data-search
                    value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <select class="form-select" onchange="window.location.href = 'vendors.php?status=' + this.value">
                    <option value="">All Status</option>
                    <option value="active" <?php echo $status_filter === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="suspended" <?php echo $status_filter === 'suspended' ? 'selected' : ''; ?>>Suspended
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <a href="vendors.php" class="btn btn-secondary w-100">Reset Filters</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Business</th>
                        <th>Contact</th>
                        <th>Products</th>
                        <th>Earnings</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vendors as $vendor): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="../uploads/profiles/<?php echo htmlspecialchars($vendor['profile_img']); ?>"
                                    alt="Profile" class="rounded-circle me-2" width="40" height="40">
                                <div>
                                    <strong><?php echo htmlspecialchars($vendor['first_name'] . ' ' . $vendor['last_name']); ?></strong>
                                    <br><small
                                        class="text-muted">@<?php echo htmlspecialchars($vendor['username']); ?></small>
                                </div>
                            </div>
                        </td>
                        <td><?php echo htmlspecialchars($vendor['business_name'] ?? 'N/A'); ?></td>
                        <td>
                            <div><?php echo htmlspecialchars($vendor['email']); ?></div>
                            <small class="text-muted"><?php echo htmlspecialchars($vendor['phone']); ?></small>
                        </td>
                        <td>
                            <span class="badge bg-primary"><?php echo $vendor['product_count']; ?> products</span>
                        </td>
                        <td>
                            <span
                                class="fw-bold text-success">$<?php echo number_format($vendor['total_earnings'] ?? 0, 2); ?></span>
                        </td>
                        <td>
                            <span class="badge bg-<?php echo $vendor['status'] === 'active' ? 'success' : 'danger'; ?>">
                                <?php echo ucfirst($vendor['status']); ?>
                            </span>
                        </td>
                        <td><?php echo date('M d, Y', strtotime($vendor['created_at'])); ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="vendor_details.php?id=<?php echo $vendor['user_id']; ?>"
                                    class="btn btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="?action=toggle_status&id=<?php echo $vendor['user_id']; ?>"
                                    class="btn btn-outline-<?php echo $vendor['status'] === 'active' ? 'warning' : 'success'; ?>"
                                    data-bs-toggle="tooltip"
                                    title="<?php echo $vendor['status'] === 'active' ? 'Suspend' : 'Activate'; ?>"
                                    onclick="return confirm('Are you sure?')">
                                    <i
                                        class="bi bi-<?php echo $vendor['status'] === 'active' ? 'pause' : 'play'; ?>"></i>
                                </a>
                                <a href="../messages.php?to=<?php echo $vendor['user_id']; ?>"
                                    class="btn btn-outline-info" data-bs-toggle="tooltip" title="Send Message">
                                    <i class="bi bi-envelope"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($vendors)): ?>
        <div class="text-center py-5">
            <i class="bi bi-shop display-1 text-muted"></i>
            <h4 class="text-muted mt-3">No vendors found</h4>
            <p class="text-muted">Try adjusting your search filters.</p>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>