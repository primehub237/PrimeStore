<?php
// cron/update_commissions.php
include '../includes/connect.php';

// Commission rate from settings table
$commissionQuery = $conn->query("SELECT value FROM settings WHERE name='commission_rate'");
$commissionRate = $commissionQuery->num_rows > 0 ? $commissionQuery->fetch_assoc()['value'] : 10;

// Get all vendor-delivered orders not yet processed
$sql = "SELECT * FROM orders WHERE product_type='vendor' AND order_status='Delivered' AND commission_status='pending'";
$result = $conn->query($sql);

while ($order = $result->fetch_assoc()) {
    $orderId = $order['order_id'];
    $vendorId = $order['vendor_id'];
    $total = $order['total_price'];

    $commission = ($commissionRate / 100) * $total;
    $vendorEarning = $total - $commission;

    // Update vendor balance
    $conn->query("UPDATE vendors SET balance = balance + $vendorEarning WHERE vendor_id='$vendorId'");

    // Record commission
    $conn->query("INSERT INTO commissions (order_id, vendor_id, amount, commission_amount, created_at)
                  VALUES ('$orderId', '$vendorId', '$vendorEarning', '$commission', NOW())");

    // Mark as processed
    $conn->query("UPDATE orders SET commission_status='processed' WHERE order_id='$orderId'");
}

// Log cron run
$conn->query("INSERT INTO cron_logs (cron_type, run_time, status) VALUES ('Commission Update', NOW(), 'success')");

echo "Commissions updated at " . date("Y-m-d H:i:s");
?>
