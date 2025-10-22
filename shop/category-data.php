<?php
// shop/category-data.php
// Product database - Replace with actual database queries

$products = [
    [
        'id' => 1,
        'name' => 'AMD Ryzen 9 7950X Desktop Processor',
        'brand' => 'AMD',
        'price' => 549.99,
        'old_price' => 699.99,
        'rating' => 4.8,
        'reviews' => 2847,
        'category' => 'Processors',
        'image' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=400',
        'badge' => 'HOT',
        'stock' => 15,
        'free_shipping' => true,
        'description' => '16 Cores, 32 Threads, 5.7 GHz Max Boost'
    ],
    [
        'id' => 2,
        'name' => 'NVIDIA GeForce RTX 4090 Graphics Card',
        'brand' => 'NVIDIA',
        'price' => 1599.99,
        'old_price' => null,
        'rating' => 4.9,
        'reviews' => 3421,
        'category' => 'Graphics Cards',
        'image' => 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=400',
        'badge' => 'NEW',
        'stock' => 8,
        'free_shipping' => true,
        'description' => '24GB GDDR6X, Ray Tracing, DLSS 3.0'
    ],
    [
        'id' => 3,
        'name' => 'Corsair Vengeance DDR5 32GB RAM Kit',
        'brand' => 'Corsair',
        'price' => 159.99,
        'old_price' => 199.99,
        'rating' => 4.7,
        'reviews' => 1856,
        'category' => 'Memory',
        'image' => 'https://images.unsplash.com/photo-1541140532154-b024d705b90a?w=400',
        'badge' => 'SALE',
        'stock' => 25,
        'free_shipping' => false,
        'description' => '32GB (2x16GB), 5600MHz, RGB Lighting'
    ],
    [
        'id' => 4,
        'name' => 'Samsung 970 EVO Plus 2TB NVMe SSD',
        'brand' => 'Samsung',
        'price' => 129.99,
        'old_price' => null,
        'rating' => 4.8,
        'reviews' => 4267,
        'category' => 'Storage',
        'image' => 'https://images.unsplash.com/photo-1531492746076-161ca9bcad58?w=400',
        'badge' => null,
        'stock' => 32,
        'free_shipping' => true,
        'description' => '2TB, 3500MB/s Read, 3300MB/s Write'
    ],
    [
        'id' => 5,
        'name' => 'ASUS ROG Swift 27" 240Hz Gaming Monitor',
        'brand' => 'ASUS',
        'price' => 699.99,
        'old_price' => 799.99,
        'rating' => 4.9,
        'reviews' => 1523,
        'category' => 'Monitors',
        'image' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=400',
        'badge' => 'HOT',
        'stock' => 12,
        'free_shipping' => true,
        'description' => '27" QHD, 240Hz, 1ms, G-SYNC, HDR'
    ],
    [
        'id' => 6,
        'name' => 'Logitech G Pro X Superlight Wireless Mouse',
        'brand' => 'Logitech',
        'price' => 149.99,
        'old_price' => null,
        'rating' => 4.6,
        'reviews' => 892,
        'category' => 'Mice',
        'image' => 'https://images.unsplash.com/photo-1527814050087-3793815479db?w=400',
        'badge' => null,
        'stock' => 45,
        'free_shipping' => false,
        'description' => 'Ultra-lightweight, HERO 25K Sensor, 70hr Battery'
    ],
    [
        'id' => 7,
        'name' => 'Intel Core i9-13900K Desktop Processor',
        'brand' => 'Intel',
        'price' => 589.99,
        'old_price' => 649.99,
        'rating' => 4.7,
        'reviews' => 2134,
        'category' => 'Processors',
        'image' => 'https://images.unsplash.com/photo-1555617981-dac3880eac6e?w=400',
        'badge' => 'HOT',
        'stock' => 18,
        'free_shipping' => true,
        'description' => '24 Cores, 32 Threads, 5.8 GHz Max Turbo'
    ],
    [
        'id' => 8,
        'name' => 'AMD Radeon RX 7900 XTX Graphics Card',
        'brand' => 'AMD',
        'price' => 999.99,
        'old_price' => null,
        'rating' => 4.6,
        'reviews' => 1876,
        'category' => 'Graphics Cards',
        'image' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=400',
        'badge' => 'NEW',
        'stock' => 14,
        'free_shipping' => true,
        'description' => '24GB GDDR6, RDNA 3, Ray Tracing'
    ],
    [
        'id' => 9,
        'name' => 'G.Skill Trident Z5 RGB 64GB DDR5 RAM',
        'brand' => 'G.Skill',
        'price' => 299.99,
        'old_price' => 349.99,
        'rating' => 4.8,
        'reviews' => 743,
        'category' => 'Memory',
        'image' => 'https://images.unsplash.com/photo-1562976540-1502c2145186?w=400',
        'badge' => 'SALE',
        'stock' => 19,
        'free_shipping' => true,
        'description' => '64GB (2x32GB), 6000MHz, CL36, RGB'
    ],
    [
        'id' => 10,
        'name' => 'Western Digital Black SN850X 4TB NVMe',
        'brand' => 'Western Digital',
        'price' => 349.99,
        'old_price' => null,
        'rating' => 4.7,
        'reviews' => 1532,
        'category' => 'Storage',
        'image' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=400',
        'badge' => null,
        'stock' => 28,
        'free_shipping' => true,
        'description' => '4TB, PCIe Gen4, 7300MB/s Read'
    ],
    [
        'id' => 11,
        'name' => 'LG UltraGear 32" 4K 144Hz Monitor',
        'brand' => 'LG',
        'price' => 799.99,
        'old_price' => 899.99,
        'rating' => 4.8,
        'reviews' => 2341,
        'category' => 'Monitors',
        'image' => 'https://images.unsplash.com/photo-1593640495253-23196b27a87f?w=400',
        'badge' => 'HOT',
        'stock' => 9,
        'free_shipping' => true,
        'description' => '32" 4K UHD, 144Hz, HDR600, Nano IPS'
    ],
    [
        'id' => 12,
        'name' => 'Razer DeathAdder V3 Pro Wireless',
        'brand' => 'Razer',
        'price' => 139.99,
        'old_price' => null,
        'rating' => 4.7,
        'reviews' => 1245,
        'category' => 'Mice',
        'image' => 'https://images.unsplash.com/photo-1615663245857-ac93bb7c39e7?w=400',
        'badge' => null,
        'stock' => 37,
        'free_shipping' => false,
        'description' => 'Focus Pro 30K Sensor, 90hr Battery'
    ],
    [
        'id' => 13,
        'name' => 'Corsair K100 RGB Mechanical Keyboard',
        'brand' => 'Corsair',
        'price' => 229.99,
        'old_price' => 279.99,
        'rating' => 4.6,
        'reviews' => 1678,
        'category' => 'Keyboards',
        'image' => 'https://images.unsplash.com/photo-1595225476474-87563907a212?w=400',
        'badge' => 'SALE',
        'stock' => 22,
        'free_shipping' => true,
        'description' => 'Cherry MX Speed, RGB, Aluminum Frame'
    ],
    [
        'id' => 14,
        'name' => 'MSI MAG B650 Tomahawk WiFi Motherboard',
        'brand' => 'MSI',
        'price' => 249.99,
        'old_price' => null,
        'rating' => 4.7,
        'reviews' => 892,
        'category' => 'Motherboards',
        'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=400',
        'badge' => null,
        'stock' => 16,
        'free_shipping' => true,
        'description' => 'AM5, DDR5, PCIe 5.0, WiFi 6E'
    ],
    [
        'id' => 15,
        'name' => 'NZXT Kraken Elite 360 RGB AIO Cooler',
        'brand' => 'NZXT',
        'price' => 289.99,
        'old_price' => 329.99,
        'rating' => 4.8,
        'reviews' => 1456,
        'category' => 'Cooling',
        'image' => 'https://images.unsplash.com/photo-1587202372583-49330a15584d?w=400',
        'badge' => 'NEW',
        'stock' => 11,
        'free_shipping' => true,
        'description' => '360mm, LCD Display, RGB Fans, 2.36" Screen'
    ],
    [
        'id' => 16,
        'name' => 'SteelSeries Apex Pro TKL Keyboard',
        'brand' => 'SteelSeries',
        'price' => 189.99,
        'old_price' => null,
        'rating' => 4.7,
        'reviews' => 1089,
        'category' => 'Keyboards',
        'image' => 'https://images.unsplash.com/photo-1511467687858-23d96c32e4ae?w=400',
        'badge' => null,
        'stock' => 29,
        'free_shipping' => false,
        'description' => 'OmniPoint 2.0 Switches, TKL, OLED Display'
    ],
    [
        'id' => 17,
        'name' => 'Crucial P5 Plus 2TB NVMe PCIe 4.0 SSD',
        'brand' => 'Crucial',
        'price' => 159.99,
        'old_price' => 199.99,
        'rating' => 4.6,
        'reviews' => 2567,
        'category' => 'Storage',
        'image' => 'https://images.unsplash.com/photo-1624823183493-ed5832f48f18?w=400',
        'badge' => 'SALE',
        'stock' => 41,
        'free_shipping' => true,
        'description' => '2TB, 6600MB/s Read, 5000MB/s Write'
    ],
    [
        'id' => 18,
        'name' => 'ASUS ROG Strix Z790-E Gaming WiFi',
        'brand' => 'ASUS',
        'price' => 449.99,
        'old_price' => null,
        'rating' => 4.9,
        'reviews' => 734,
        'category' => 'Motherboards',
        'image' => 'https://images.unsplash.com/photo-1591489378430-4f9d0c2c9d96?w=400',
        'badge' => 'HOT',
        'stock' => 7,
        'free_shipping' => true,
        'description' => 'LGA 1700, DDR5, PCIe 5.0, WiFi 6E, RGB'
    ]
];

// Define all available categories explicitly
$categories = [
    'Processors',
    'Graphics Cards',
    'Memory',
    'Storage',
    'Monitors',
    'Keyboards',
    'Mice',
    'Motherboards',
    'Cooling'
];

// Get all unique brands
$brands = array_unique(array_column($products, 'brand'));
sort($brands);

// Category descriptions
$categoryDescriptions = [
    'all' => 'Explore our complete collection of premium PC components',
    'Processors' => 'High-performance CPUs from AMD and Intel',
    'Graphics Cards' => 'Cutting-edge GPUs for gaming and content creation',
    'Memory' => 'Fast and reliable RAM for your system',
    'Storage' => 'SSDs and HDDs with blazing fast speeds',
    'Monitors' => 'Crystal clear displays for work and gaming',
    'Keyboards' => 'Mechanical and gaming keyboards',
    'Mice' => 'Precision gaming and productivity mice',
    'Motherboards' => 'Foundation for your ultimate PC build',
    'Cooling' => 'Keep your system running cool and quiet'
];

// Related categories mapping
$relatedCategories = [
    'Processors' => ['Motherboards', 'Cooling', 'Memory'],
    'Graphics Cards' => ['Monitors', 'Processors', 'Cooling'],
    'Memory' => ['Motherboards', 'Processors', 'Storage'],
    'Storage' => ['Motherboards', 'Memory', 'Processors'],
    'Monitors' => ['Graphics Cards', 'Keyboards', 'Mice'],
    'Keyboards' => ['Mice', 'Monitors', 'Processors'],
    'Mice' => ['Keyboards', 'Monitors', 'Graphics Cards'],
    'Motherboards' => ['Processors', 'Memory', 'Storage'],
    'Cooling' => ['Processors', 'Graphics Cards', 'Motherboards'],
    'all' => ['Processors', 'Graphics Cards', 'Memory']
];

// Best deals - products with highest discount
function getBestDeals($products, $limit = 6) {
    $deals = array_filter($products, function($product) {
        return isset($product['old_price']) && $product['old_price'] > 0;
    });
    
    usort($deals, function($a, $b) {
        $discountA = (($a['old_price'] - $a['price']) / $a['old_price']) * 100;
        $discountB = (($b['old_price'] - $b['price']) / $b['old_price']) * 100;
        return $discountB - $discountA;
    });
    
    return array_slice($deals, 0, $limit);
}

// Top rated products
function getTopRated($products, $limit = 6) {
    $topRated = $products;
    usort($topRated, function($a, $b) {
        if ($b['rating'] == $a['rating']) {
            return $b['reviews'] - $a['reviews'];
        }
        return $b['rating'] - $a['rating'];
    });
    
    return array_slice($topRated, 0, $limit);
}

// Most popular (by reviews)
function getMostPopular($products, $limit = 6) {
    $popular = $products;
    usort($popular, function($a, $b) {
        return $b['reviews'] - $a['reviews'];
    });
    
    return array_slice($popular, 0, $limit);
}

// Filter products by category
function filterByCategory($products, $category) {
    if ($category === 'all' || empty($category)) {
        return $products;
    }
    
    return array_filter($products, function($product) use ($category) {
        return strtolower($product['category']) === strtolower($category);
    });
}

// Get price range for category
function getPriceRange($products) {
    if (empty($products)) {
        return ['min' => 0, 'max' => 0];
    }
    
    $prices = array_column($products, 'price');
    return [
        'min' => min($prices),
        'max' => max($prices)
    ];
}

// Calculate discount percentage
function getDiscountPercentage($oldPrice, $newPrice) {
    if (!$oldPrice || $oldPrice <= 0) {
        return 0;
    }
    return round((($oldPrice - $newPrice) / $oldPrice) * 100);
}

// Format price
function formatPrice($price) {
    return '$' . number_format($price, 2);
}

// Check if product is on sale
function isOnSale($product) {
    return isset($product['old_price']) && $product['old_price'] > 0;
}

// Get product availability status
function getAvailabilityStatus($stock) {
    if ($stock > 20) {
        return ['status' => 'In Stock', 'class' => 'in-stock'];
    } elseif ($stock > 0) {
        return ['status' => "Only $stock left", 'class' => 'low-stock'];
    } else {
        return ['status' => 'Out of Stock', 'class' => 'out-of-stock'];
    }
}
?>