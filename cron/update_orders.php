<?php
// cron/update_orders.php
include '../includes/connect.php';

// Fetch AliExpress-based orders only
$sql = "SELECT * FROM orders WHERE product_type = 'aliexpress' AND order_status != 'Delivered'";
$result = $conn->query($sql);

while ($order = $result->fetch_assoc()) {
    $orderId = $order['order_id'];
    $trackingId = $order['tracking_id'];

    // Example simulated API response
    $apiResponse = [
        'success' => true,
        'status' => (rand(1, 3) == 3 ? 'Delivered' : 'In Transit'),
    ];

    if ($apiResponse['success']) {
        $status = $apiResponse['status'];
        $conn->query("UPDATE orders SET order_status='$status', updated_at=NOW() WHERE order_id='$orderId'");
    }
}

// Log cron job
$conn->query("INSERT INTO cron_logs (cron_type, run_time, status) VALUES ('Order Update', NOW(), 'success')");

echo "Order statuses updated at " . date("Y-m-d H:i:s");
?>
