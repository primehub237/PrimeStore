            </div>
            </main>

            <!-- Mobile Overlay -->
            <div class="mobile-overlay" id="mobileOverlay"></div>

            <!-- Mobile Bottom Navigation - FIXED: Proper bottom positioning -->
            <nav class="bottom-nav">
                <div class="bottom-nav-items">
                    <a href="<?php echo $base_path; ?>dashboard.php"
                        class="bottom-nav-item <?php echo $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="<?php echo $base_path; ?>modules/users.php"
                        class="bottom-nav-item <?php echo $current_page == 'users.php' ? 'active' : ''; ?>">
                        <i class="bi bi-people"></i>
                        <span>Users</span>
                    </a>
                    <a href="<?php echo $base_path; ?>modules/products.php"
                        class="bottom-nav-item <?php echo $current_page == 'products.php' ? 'active' : ''; ?>">
                        <i class="bi bi-box"></i>
                        <span>Products</span>
                    </a>
                    <a href="<?php echo $base_path; ?>modules/orders.php"
                        class="bottom-nav-item <?php echo $current_page == 'orders.php' ? 'active' : ''; ?>">
                        <i class="bi bi-cart"></i>
                        <span>Orders</span>
                    </a>
                    <a href="<?php echo $base_path; ?>modules/messages.php"
                        class="bottom-nav-item <?php echo $current_page == 'messages.php' ? 'active' : ''; ?>">
                        <i class="bi bi-envelope"></i>
                        <span>Messages</span>
                    </a>
                </div>
            </nav>
            </div>

            <!-- Loading Spinner -->
            <div class="loading-spinner" id="loadingSpinner">
                <div class="d-flex flex-column align-items-center">
                    <div class="spinner-border text-primary mb-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <small class="text-muted">Processing...</small>
                </div>
            </div>

            <!-- Scripts -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
            <script src="<?php echo $base_path; ?>assets/js/main.js"></script>
            </body>

            </html>