<?php
$page_title = "System Settings";
require_once '../includes/header.php';

// Handle settings update
if (isset($_POST['update_settings'])) {
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'setting_') === 0) {
            $setting_name = substr($key, 8); // Remove 'setting_' prefix
            $setting_value = sanitize_input($value);
            
            // Check if setting exists
            $stmt = $pdo->prepare("SELECT setting_id FROM settings WHERE setting_name = ?");
            $stmt->execute([$setting_name]);
            
            if ($stmt->fetch()) {
                // Update existing setting
                $stmt = $pdo->prepare("UPDATE settings SET setting_value = ?, updated_at = NOW() WHERE setting_name = ?");
                $stmt->execute([$setting_value, $setting_name]);
            } else {
                // Insert new setting
                $stmt = $pdo->prepare("INSERT INTO settings (setting_name, setting_value) VALUES (?, ?)");
                $stmt->execute([$setting_name, $setting_value]);
            }
        }
    }
    
    log_admin_action("Settings updated", "Multiple settings modified");
    header('Location: settings.php?success=Settings updated successfully');
    exit();
}

// Handle AliExpress API sync
if (isset($_POST['sync_aliexpress'])) {
    // Simulate AliExpress sync process
    $sync_result = simulateAliExpressSync();
    
    if ($sync_result['success']) {
        log_admin_action("AliExpress sync initiated", $sync_result['message']);
        header('Location: settings.php?success=' . urlencode($sync_result['message']));
    } else {
        header('Location: settings.php?error=' . urlencode($sync_result['message']));
    }
    exit();
}

// Handle system maintenance
if (isset($_POST['toggle_maintenance'])) {
    $maintenance_mode = $_POST['maintenance_mode'] === '1' ? '1' : '0';
    
    $stmt = $pdo->prepare("REPLACE INTO settings (setting_name, setting_value) VALUES ('maintenance_mode', ?)");
    $stmt->execute([$maintenance_mode]);
    
    $status = $maintenance_mode === '1' ? 'enabled' : 'disabled';
    log_admin_action("Maintenance mode $status", "");
    header('Location: settings.php?success=Maintenance mode ' . $status);
    exit();
}

// Fetch all settings
$stmt = $pdo->query("SELECT setting_name, setting_value FROM settings");
$settings_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convert to associative array for easy access
$settings = [];
foreach ($settings_data as $setting) {
    $settings[$setting['setting_name']] = $setting['setting_value'];
}

// Fetch system information
$system_info = [
    'php_version' => PHP_VERSION,
    'mysql_version' => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION),
    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
    'upload_max_filesize' => ini_get('upload_max_filesize'),
    'max_execution_time' => ini_get('max_execution_time'),
    'memory_limit' => ini_get('memory_limit')
];

// Fetch recent system logs
$stmt = $pdo->query("
    SELECT l.*, u.first_name, u.last_name 
    FROM system_logs l 
    LEFT JOIN users u ON l.user_id = u.user_id 
    ORDER BY l.created_at DESC 
    LIMIT 10
");
$recent_logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch cron job status
$stmt = $pdo->query("SELECT * FROM cron_status ORDER BY last_run DESC LIMIT 5");
$cron_status = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to simulate AliExpress sync
function simulateAliExpressSync() {
    // In a real application, this would call the AliExpress API
    sleep(2); // Simulate API call delay
    
    $success = rand(0, 1); // Simulate success/failure
    if ($success) {
        $products_synced = rand(5, 50);
        return [
            'success' => true,
            'message' => "Successfully synchronized $products_synced products from AliExpress"
        ];
    } else {
        return [
            'success' => false,
            'message' => "Failed to sync with AliExpress API. Please check your API credentials."
        ];
    }
}
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="bi bi-gear me-2"></i>System Settings
    </h1>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-arrow-repeat display-4 text-primary"></i>
                <h5 class="card-title mt-2">Sync AliExpress</h5>
                <p class="card-text text-muted">Sync products from AliExpress</p>
                <form method="POST" class="d-inline">
                    <button type="submit" name="sync_aliexpress" class="btn btn-primary">
                        <i class="bi bi-arrow-repeat"></i> Sync Now
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i
                    class="bi bi-tools display-4 text-<?php echo ($settings['maintenance_mode'] ?? '0') === '1' ? 'danger' : 'success'; ?>"></i>
                <h5 class="card-title mt-2">Maintenance Mode</h5>
                <p class="card-text text-muted">
                    <?php echo ($settings['maintenance_mode'] ?? '0') === '1' ? 'Enabled' : 'Disabled'; ?>
                </p>
                <form method="POST" class="d-inline">
                    <input type="hidden" name="maintenance_mode"
                        value="<?php echo ($settings['maintenance_mode'] ?? '0') === '1' ? '0' : '1'; ?>">
                    <button type="submit" name="toggle_maintenance"
                        class="btn btn-<?php echo ($settings['maintenance_mode'] ?? '0') === '1' ? 'success' : 'warning'; ?>">
                        <i
                            class="bi bi-<?php echo ($settings['maintenance_mode'] ?? '0') === '1' ? 'play' : 'pause'; ?>"></i>
                        <?php echo ($settings['maintenance_mode'] ?? '0') === '1' ? 'Disable' : 'Enable'; ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-database display-4 text-info"></i>
                <h5 class="card-title mt-2">Backup Database</h5>
                <p class="card-text text-muted">Create database backup</p>
                <button type="button" class="btn btn-info"
                    onclick="alert('Backup functionality would be implemented here')">
                    <i class="bi bi-download"></i> Backup Now
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="bi bi-trash display-4 text-warning"></i>
                <h5 class="card-title mt-2">Clear Cache</h5>
                <p class="card-text text-muted">Clear system cache</p>
                <button type="button" class="btn btn-warning"
                    onclick="alert('Cache clearance would be implemented here')">
                    <i class="bi bi-arrow-clockwise"></i> Clear Cache
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- General Settings -->
    <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-building me-2"></i>General Settings
                </h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="setting_site_name" class="form-label">Site Name</label>
                        <input type="text" class="form-control" id="setting_site_name" name="setting_site_name"
                            value="<?php echo htmlspecialchars($settings['site_name'] ?? 'PrimeStore'); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="setting_site_email" class="form-label">Site Email</label>
                        <input type="email" class="form-control" id="setting_site_email" name="setting_site_email"
                            value="<?php echo htmlspecialchars($settings['site_email'] ?? 'admin@primestore.com'); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="setting_currency" class="form-label">Currency</label>
                        <select class="form-select" id="setting_currency" name="setting_currency">
                            <option value="USD"
                                <?php echo ($settings['currency'] ?? 'USD') === 'USD' ? 'selected' : ''; ?>>USD ($)
                            </option>
                            <option value="EUR"
                                <?php echo ($settings['currency'] ?? 'USD') === 'EUR' ? 'selected' : ''; ?>>EUR (€)
                            </option>
                            <option value="GBP"
                                <?php echo ($settings['currency'] ?? 'USD') === 'GBP' ? 'selected' : ''; ?>>GBP (£)
                            </option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="setting_timezone" class="form-label">Timezone</label>
                        <select class="form-select" id="setting_timezone" name="setting_timezone">
                            <option value="UTC"
                                <?php echo ($settings['timezone'] ?? 'UTC') === 'UTC' ? 'selected' : ''; ?>>UTC</option>
                            <option value="America/New_York"
                                <?php echo ($settings['timezone'] ?? 'UTC') === 'America/New_York' ? 'selected' : ''; ?>>
                                Eastern Time</option>
                            <option value="Europe/London"
                                <?php echo ($settings['timezone'] ?? 'UTC') === 'Europe/London' ? 'selected' : ''; ?>>
                                London</option>
                        </select>
                    </div>

                    <button type="submit" name="update_settings" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Save General Settings
                    </button>
                </form>
            </div>
        </div>

        <!-- Commission Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-percent me-2"></i>Commission Settings
                </h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="setting_platform_commission" class="form-label">Platform Commission (%)</label>
                        <input type="number" class="form-control" id="setting_platform_commission"
                            name="setting_platform_commission" min="0" max="100" step="0.1"
                            value="<?php echo htmlspecialchars($settings['platform_commission'] ?? '15'); ?>">
                        <div class="form-text">Percentage of each sale that goes to the platform.</div>
                    </div>

                    <div class="mb-3">
                        <label for="setting_vendor_commission" class="form-label">Vendor Commission (%)</label>
                        <input type="number" class="form-control" id="setting_vendor_commission"
                            name="setting_vendor_commission" min="0" max="100" step="0.1"
                            value="<?php echo htmlspecialchars($settings['vendor_commission'] ?? '85'); ?>">
                        <div class="form-text">Percentage of each sale that goes to the vendor.</div>
                    </div>

                    <div class="mb-3">
                        <label for="setting_minimum_payout" class="form-label">Minimum Payout Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="setting_minimum_payout"
                                name="setting_minimum_payout" min="0" step="0.01"
                                value="<?php echo htmlspecialchars($settings['minimum_payout'] ?? '50.00'); ?>">
                        </div>
                        <div class="form-text">Minimum amount required for vendor payout.</div>
                    </div>

                    <button type="submit" name="update_settings" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Save Commission Settings
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <!-- AliExpress API Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-globe me-2"></i>AliExpress API Settings
                </h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="setting_aliexpress_api_key" class="form-label">API Key</label>
                        <input type="password" class="form-control" id="setting_aliexpress_api_key"
                            name="setting_aliexpress_api_key"
                            value="<?php echo htmlspecialchars($settings['aliexpress_api_key'] ?? ''); ?>"
                            placeholder="Enter your AliExpress API key">
                    </div>

                    <div class="mb-3">
                        <label for="setting_aliexpress_secret" class="form-label">API Secret</label>
                        <input type="password" class="form-control" id="setting_aliexpress_secret"
                            name="setting_aliexpress_secret"
                            value="<?php echo htmlspecialchars($settings['aliexpress_secret'] ?? ''); ?>"
                            placeholder="Enter your AliExpress API secret">
                    </div>

                    <div class="mb-3">
                        <label for="setting_sync_frequency" class="form-label">Sync Frequency</label>
                        <select class="form-select" id="setting_sync_frequency" name="setting_sync_frequency">
                            <option value="hourly"
                                <?php echo ($settings['sync_frequency'] ?? 'daily') === 'hourly' ? 'selected' : ''; ?>>
                                Hourly</option>
                            <option value="daily"
                                <?php echo ($settings['sync_frequency'] ?? 'daily') === 'daily' ? 'selected' : ''; ?>>
                                Daily</option>
                            <option value="weekly"
                                <?php echo ($settings['sync_frequency'] ?? 'daily') === 'weekly' ? 'selected' : ''; ?>>
                                Weekly</option>
                        </select>
                        <div class="form-text">How often to sync products from AliExpress.</div>
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="setting_auto_sync" name="setting_auto_sync"
                            value="1" <?php echo ($settings['auto_sync'] ?? '0') === '1' ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="setting_auto_sync">Enable automatic sync</label>
                    </div>

                    <button type="submit" name="update_settings" class="btn btn-primary">
                        <i class="bi bi-check-lg"></i> Save API Settings
                    </button>
                </form>
            </div>
        </div>

        <!-- System Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>System Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>PHP Version:</strong> <?php echo $system_info['php_version']; ?></p>
                        <p><strong>MySQL Version:</strong> <?php echo $system_info['mysql_version']; ?></p>
                        <p><strong>Server Software:</strong> <?php echo $system_info['server_software']; ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Upload Max Filesize:</strong> <?php echo $system_info['upload_max_filesize']; ?></p>
                        <p><strong>Max Execution Time:</strong> <?php echo $system_info['max_execution_time']; ?>s</p>
                        <p><strong>Memory Limit:</strong> <?php echo $system_info['memory_limit']; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cron Job Status -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2"></i>Cron Job Status
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Job Name</th>
                                <th>Last Run</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cron_status as $cron): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cron['job_name']); ?></td>
                                <td><?php echo date('M d, Y H:i', strtotime($cron['last_run'])); ?></td>
                                <td>
                                    <span
                                        class="badge bg-<?php echo $cron['status'] === 'success' ? 'success' : 'danger'; ?>">
                                        <?php echo ucfirst($cron['status']); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent System Logs -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-list-check me-2"></i>Recent System Logs
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-striped">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Action</th>
                                <th>Details</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_logs as $log): ?>
                            <tr>
                                <td>
                                    <?php if ($log['first_name']): ?>
                                    <?php echo htmlspecialchars($log['first_name'] . ' ' . $log['last_name']); ?>
                                    <?php else: ?>
                                    <span class="text-muted">System</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($log['action']); ?></td>
                                <td>
                                    <small class="text-muted"><?php echo htmlspecialchars($log['details']); ?></small>
                                </td>
                                <td><?php echo date('M d, Y H:i', strtotime($log['created_at'])); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>