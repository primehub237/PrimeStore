<?php
require_once 'auth.php';
require_admin_login();

// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);

// Determine base path based on current directory
$is_module = strpos($_SERVER['PHP_SELF'], '/modules/') !== false;
$base_path = $is_module ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - PrimeStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="<?php echo $base_path; ?>assets/css/custom.css" rel="stylesheet">
</head>

<body>
    <div class="admin-container">
        <!-- Desktop Sidebar -->
        <nav id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h4>PrimeStore</h4>
                <p>Admin Panel</p>
            </div>

            <div class="sidebar-nav">
                <a class="nav-link <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>"
                    href="<?php echo $base_path; ?>dashboard.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a class="nav-link <?php echo $current_page == 'users.php' ? 'active' : ''; ?>"
                    href="<?php echo $base_path; ?>modules/users.php">
                    <i class="bi bi-people"></i> Users
                </a>
                <a class="nav-link <?php echo $current_page == 'vendors.php' ? 'active' : ''; ?>"
                    href="<?php echo $base_path; ?>modules/vendors.php">
                    <i class="bi bi-shop"></i> Vendors
                </a>
                <a class="nav-link <?php echo $current_page == 'products.php' ? 'active' : ''; ?>"
                    href="<?php echo $base_path; ?>modules/products.php">
                    <i class="bi bi-box"></i> Products
                </a>
                <a class="nav-link <?php echo $current_page == 'orders.php' ? 'active' : ''; ?>"
                    href="<?php echo $base_path; ?>modules/orders.php">
                    <i class="bi bi-cart"></i> Orders
                </a>
                <a class="nav-link <?php echo $current_page == 'payments.php' ? 'active' : ''; ?>"
                    href="<?php echo $base_path; ?>modules/payments.php">
                    <i class="bi bi-credit-card"></i> Payments
                </a>
                <a class="nav-link <?php echo $current_page == 'commissions.php' ? 'active' : ''; ?>"
                    href="<?php echo $base_path; ?>modules/commissions.php">
                    <i class="bi bi-graph-up"></i> Commissions
                </a>
                <a class="nav-link <?php echo $current_page == 'messages.php' ? 'active' : ''; ?>"
                    href="<?php echo $base_path; ?>modules/messages.php">
                    <i class="bi bi-envelope"></i> Messages
                </a>
                <a class="nav-link <?php echo $current_page == 'settings.php' ? 'active' : ''; ?>"
                    href="<?php echo $base_path; ?>modules/settings.php">
                    <i class="bi bi-gear"></i> Settings
                </a>
                <div class="mt-4 px-3">
                    <a class="nav-link text-warning" href="<?php echo $base_path; ?>logout.php">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <div class="content-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <button id="mobileMenuBtn" class="btn btn-outline-primary me-3 d-lg-none">
                            <i class="bi bi-list"></i>
                        </button>
                        <h1 class="h3 mb-0"><?php echo $page_title ?? 'Dashboard'; ?></h1>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-2"></i><?php echo $_SESSION['admin_name']; ?>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo $base_path; ?>logout.php"><i
                                            class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Body -->
            <div class="content-body">
                <!-- Success/Error Messages -->
                <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show animate-fade-in-up" role="alert">
                    <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($_GET['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show animate-fade-in-up" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($_GET['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>