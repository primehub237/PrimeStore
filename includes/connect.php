<?php
// includes/connect.php
$host = "localhost";
$user = "root";  // change if on live server
$pass = "";
$dbname = "primehub_db";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
