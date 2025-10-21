<?php
session_start();
ob_start();

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'rolandor2020');
define('DB_NAME', 'primestore');
define('BASE_URL', 'http://localhost/primestore/admin/');

// Create database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Security functions
function sanitize_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Authentication functions
// function is_admin_logged_in() {
//     return isset($_SESSION['admin_id']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin';
// }

// function require_admin_login() {
//     if (!is_admin_logged_in()) {
//         header('Location: login.php');
//         exit();
//     }
// }

// function admin_login($email, $password) {
//     global $pdo;
    
//     $stmt = $pdo->prepare("SELECT user_id, first_name, last_name, email, password FROM users WHERE email = ? AND role = 'admin' AND status = 'active'");
//     $stmt->execute([$email]);
//     $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
//     if ($admin && password_verify($password, $admin['password'])) {
//         $_SESSION['admin_id'] = $admin['user_id'];
//         $_SESSION['admin_name'] = $admin['first_name'] . ' ' . $admin['last_name'];
//         $_SESSION['admin_email'] = $admin['email'];
//         $_SESSION['admin_role'] = 'admin';
//         return true;
//     }
//     return false;
// }

// function log_admin_action($action, $details = '') {
//     global $pdo;
    
//     $user_id = $_SESSION['admin_id'] ?? null;
//     $stmt = $pdo->prepare("INSERT INTO system_logs (user_id, action, details) VALUES (?, ?, ?)");
//     $stmt->execute([$user_id, $action, $details]);
// }
?>