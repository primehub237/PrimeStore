<?php
// includes/functions.php

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirect($url) {
    echo "<script>window.location.href='$url';</script>";
    exit;
}
?>
