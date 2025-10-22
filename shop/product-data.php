<?php
// shop/product-data.php
// This file contains all product data in a structured array format

$products = [
    // Processors
    [
        'id' => 1,
        'name' => 'Intel Core i7-14700K 20-Core 28-Thread CPU Processor',
        'brand' => 'Intel',
        'category' => 'Processors',
        'price' => 326.49,
        'old_price' => 399.99,
        'rating' => 4.8,
        'reviews' => 1247,
        'stock' => 45,
        'free_shipping' => true,
        'image' => 'https://gamersnexus.net/u/styles/large_responsive_no_watermark_/public/inline-images/vlcsnap-2023-11-07-14h04m00s690.jpg.webp',
        'badge' => 'SALE'
    ],

    // Capture Cards
    [
        'id' => 45,
        'name' => 'Elgato Game Capture 4K60 S+ External Capture Card',
        'brand' => 'Elgato',
        'category' => 'Capture Cards',
        'price' => 399.99,
        'old_price' => null,
        'rating' => 4.9,
        'reviews' => 1567,
        'stock' => 45,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=500&q=80',
        'badge' => 'HOT'
    ],
    [
        'id' => 46,
        'name' => 'AVerMedia Live Gamer 4K GC573 PCIe Capture Card',
        'brand' => 'AVerMedia',
        'category' => 'Capture Cards',
        'price' => 249.99,
        'old_price' => 299.99,
        'rating' => 4.7,
        'reviews' => 987,
        'stock' => 67,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1591488320449-011701bb6704?w=500&q=80',
        'badge' => 'SALE'
    ],

    // Cables & Adapters
    [
        'id' => 47,
        'name' => 'Cable Matters 8K DisplayPort to HDMI Cable 6ft',
        'brand' => 'Cable Matters',
        'category' => 'Cables',
        'price' => 19.99,
        'old_price' => 29.99,
        'rating' => 4.7,
        'reviews' => 4567,
        'stock' => 456,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500&q=80',
        'badge' => null
    ],
    [
        'id' => 48,
        'name' => 'Anker USB-C to HDMI 8K Adapter Cable',
        'brand' => 'Anker',
        'category' => 'Cables',
        'price' => 24.99,
        'old_price' => null,
        'rating' => 4.8,
        'reviews' => 3421,
        'stock' => 589,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1625948515291-69613efd103f?w=500&q=80',
        'badge' => 'HOT'
    ],

    // Thermal Paste
    [
        'id' => 49,
        'name' => 'Thermal Grizzly Kryonaut Extreme Thermal Paste 2g',
        'brand' => 'Thermal Grizzly',
        'category' => 'Thermal Paste',
        'price' => 14.99,
        'old_price' => 19.99,
        'rating' => 4.9,
        'reviews' => 8765,
        'stock' => 789,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1563968743333-044cef800494?w=500&q=80',
        'badge' => 'HOT'
    ],
    [
        'id' => 50,
        'name' => 'Arctic MX-6 High Performance Thermal Compound 4g',
        'brand' => 'Arctic',
        'category' => 'Thermal Paste',
        'price' => 9.99,
        'old_price' => null,
        'rating' => 4.8,
        'reviews' => 6543,
        'stock' => 1234,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=500&q=80',
        'badge' => null
    ],

    // Additional products to reach 60+ items
    [
        'id' => 51,
        'name' => 'Seagate Barracuda 4TB Internal Hard Drive HDD',
        'brand' => 'Seagate',
        'category' => 'Storage',
        'price' => 79.99,
        'old_price' => 99.99,
        'rating' => 4.6,
        'reviews' => 5432,
        'stock' => 345,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=500&q=80',
        'badge' => 'SALE'
    ],
    [
        'id' => 52,
        'name' => 'Crucial MX500 2TB SATA 2.5" Internal SSD',
        'brand' => 'Crucial',
        'category' => 'Storage',
        'price' => 149.99,
        'old_price' => 199.99,
        'rating' => 4.8,
        'reviews' => 7654,
        'stock' => 234,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1531492746076-161ca9bcad58?w=500&q=80',
        'badge' => null
    ],
    [
        'id' => 53,
        'name' => 'be quiet! Dark Rock Pro 4 CPU Cooler',
        'brand' => 'be quiet!',
        'category' => 'Cooling',
        'price' => 89.99,
        'old_price' => null,
        'rating' => 4.9,
        'reviews' => 3456,
        'stock' => 112,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1587202372583-49330a15584d?w=500&q=80',
        'badge' => 'HOT'
    ],
    [
        'id' => 54,
        'name' => 'Fractal Design Torrent RGB Compact ATX Case',
        'brand' => 'Fractal Design',
        'category' => 'Cases',
        'price' => 189.99,
        'old_price' => 229.99,
        'rating' => 4.8,
        'reviews' => 876,
        'stock' => 56,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1587202372634-32705e3bf49c?w=500&q=80',
        'badge' => 'NEW'
    ],
    [
        'id' => 55,
        'name' => 'Thermaltake Toughpower GF3 1000W 80+ Gold PSU',
        'brand' => 'Thermaltake',
        'category' => 'Power Supplies',
        'price' => 159.99,
        'old_price' => 199.99,
        'rating' => 4.7,
        'reviews' => 1234,
        'stock' => 89,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?w=500&q=80',
        'badge' => 'SALE'
    ],
    [
        'id' => 56,
        'name' => 'ASUS TUF Gaming GeForce RTX 4060 Ti 16GB',
        'brand' => 'ASUS',
        'category' => 'Graphics Cards',
        'price' => 549.99,
        'old_price' => 649.99,
        'rating' => 4.7,
        'reviews' => 2134,
        'stock' => 67,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1591405351990-4726e331f141?w=500&q=80',
        'badge' => null
    ],
    [
        'id' => 57,
        'name' => 'AMD Ryzen 5 7600X 6-Core Desktop Processor',
        'brand' => 'AMD',
        'category' => 'Processors',
        'price' => 249.99,
        'old_price' => 299.99,
        'rating' => 4.8,
        'reviews' => 1876,
        'stock' => 134,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=500&q=80',
        'badge' => 'HOT'
    ],
    [
        'id' => 58,
        'name' => 'MSI MAG B760 TOMAHAWK WiFi DDR4 Gaming Motherboard',
        'brand' => 'MSI',
        'category' => 'Motherboards',
        'price' => 189.99,
        'old_price' => null,
        'rating' => 4.6,
        'reviews' => 654,
        'stock' => 78,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=500&q=80',
        'badge' => null
    ],
    [
        'id' => 59,
        'name' => 'Team Group T-Force Delta RGB 16GB DDR4 3200MHz',
        'brand' => 'Team Group',
        'category' => 'Memory',
        'price' => 44.99,
        'old_price' => 59.99,
        'rating' => 4.5,
        'reviews' => 2876,
        'stock' => 456,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1541348263662-e068662d82af?w=500&q=80',
        'badge' => 'SALE'
    ],
    [
        'id' => 60,
        'name' => 'Dell S2722DGM 27" 1440p 165Hz Curved Gaming Monitor',
        'brand' => 'Dell',
        'category' => 'Monitors',
        'price' => 279.99,
        'old_price' => 349.99,
        'rating' => 4.7,
        'reviews' => 3421,
        'stock' => 89,
        'free_shipping' => true,
        'image' => 'https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?w=500&q=80',
        'badge' => 'HOT'
    ]
];
// You can add more products following the same structure
// Each product should have: id, name, brand, category, price, old_price, rating, reviews, stock, free_shipping, image, badge

?>