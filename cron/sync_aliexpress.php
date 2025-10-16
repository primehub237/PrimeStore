<?php
// cron/sync_aliexpress.php
include '../includes/connect.php';

// AliExpress API credentials
$apiKey = "YOUR_ALIEXPRESS_API_KEY";
$apiEndpoint = "https://api-seller.aliexpress.com/sync";

// Fetch all PrimeHub AliExpress products
$sql = "SELECT * FROM products WHERE product_type = 'aliexpress'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($product = $result->fetch_assoc()) {
        $productId = $product['product_id'];
        $aliexpressLink = $product['aliexpress_link'];

        // Fake example API request (replace with real AliExpress API call)
        // For example: use cURL to fetch updated stock and price
        $apiResponse = [
            'success' => true,
            'new_price' => rand(1000, 5000),
            'new_stock' => rand(5, 50)
        ];

        if ($apiResponse['success']) {
            $newPrice = $apiResponse['new_price'];
            $newStock = $apiResponse['new_stock'];

            $update = $conn->query("UPDATE products SET price='$newPrice', stock='$newStock' WHERE product_id='$productId'");
        }
    }
}

// Log cron execution
$conn->query("INSERT INTO cron_logs (cron_type, run_time, status) VALUES ('AliExpress Sync', NOW(), 'success')");

echo "AliExpress sync complete at " . date("Y-m-d H:i:s");
?>
