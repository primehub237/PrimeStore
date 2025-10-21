<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

if (isset($_SESSION['admin_id'])) {
    log_admin_action("Admin logout");
}

// Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: login.php');
exit();
?>