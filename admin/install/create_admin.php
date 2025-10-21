<?php
require_once '../includes/config.php';

try {
    // Check if admin already exists
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users WHERE role = 'admin'");
    $admin_count = $stmt->fetch()['count'];
    
    if ($admin_count > 0) {
        die("Admin user already exists!");
    }
    
    // Create admin user
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (role, first_name, last_name, username, email, password, status) VALUES ('admin', 'System', 'Administrator', 'admin', 'admin@primestore.com', ?, 'active')");
    $stmt->execute([$password]);
    
    echo "Admin user created successfully!<br>";
    echo "Email: admin@primestore.com<br>";
    echo "Password: admin123<br>";
    echo "<strong>Please change the password after first login!</strong>";
    
} catch (PDOException $e) {
    die("Error creating admin user: " . $e->getMessage());
}
?>