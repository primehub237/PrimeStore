<?php
require_once 'config.php';

function is_admin_logged_in() {
    return isset($_SESSION['admin_id']) && isset($_SESSION['admin_role']) && $_SESSION['admin_role'] === 'admin';
}

function require_admin_login() {
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit();
    }
}

function admin_login($email, $password) {
    global $pdo;
    
    $stmt = $pdo->prepare("SELECT user_id, first_name, last_name, email, password FROM users WHERE email = ? AND role = 'admin' AND status = 'active'");
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['user_id'];
        $_SESSION['admin_name'] = $admin['first_name'] . ' ' . $admin['last_name'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_role'] = 'admin';
        return true;
    }
    return false;
}

function log_admin_action($action, $details = '') {
    global $pdo;
    
    $stmt = $pdo->prepare("INSERT INTO system_logs (user_id, action, details) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['admin_id'], $action, $details]);
}
?>