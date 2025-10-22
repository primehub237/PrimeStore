<?php
// includes/data.php
// This file contains all product data
// Later, replace these arrays with database queries

// Recently Viewed Products
$recentlyViewed = [
    [
        'id' => 1,
        'name' => 'Samsung Galaxy S23 5G 256GB',
        'price' => 899.99,
        'image' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=300',
        'condition' => 'Brand New',
        'category' => 'Electronics'
    ],
    [
        'id' => 2,
        'name' => 'Apple iPhone 14 Pro Max',
        'price' => 1199.99,
        'image' => 'https://www.macstoreonline.com.mx/img/cx/media/all-products/iPhone/iPhone_14_Plus.png',
        'condition' => 'Brand New',
        'category' => 'Electronics'
    ],
    [
        'id' => 3,
        'name' => 'Sony WH-1000XM5 Headphones',
        'price' => 399.99,
        'image' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=300',
        'condition' => 'Brand New',
        'category' => 'Electronics'
    ]
];

// Categories
$categories = [
    [
        'id' => 1,
        'name' => 'Laptops',
        'image' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=300',
        'icon' => 'fa-laptop',
        'link' => 'category.php?cat=laptops'
    ],
    [
        'id' => 2,
        'name' => 'Computer parts',
        'image' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=300',
        'icon' => 'fa-microchip',
        'link' => 'category.php?cat=computer-parts'
    ],
    [
        'id' => 3,
        'name' => 'Smartphones',
        'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=300',
        'icon' => 'fa-mobile-alt',
        'link' => 'category.php?cat=smartphones'
    ],
    [
        'id' => 4,
        'name' => 'Enterprise networking',
        'image' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?w=300',
        'icon' => 'fa-network-wired',
        'link' => 'category.php?cat=networking'
    ],
    [
        'id' => 5,
        'name' => 'Tablets and eBooks',
        'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=300',
        'icon' => 'fa-tablet-alt',
        'link' => 'category.php?cat=tablets'
    ],
    [
        'id' => 6,
        'name' => 'Storage and data',
        'image' => 'https://images.unsplash.com/photo-1597872200969-2b65d56bd16b?w=300',
        'icon' => 'fa-hdd',
        'link' => 'category.php?cat=storage'
    ],
    [
        'id' => 7,
        'name' => 'Cameras',
        'image' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=300',
        'icon' => 'fa-camera',
        'link' => 'category.php?cat=cameras'
    ]
];

// Trending Products
$trendingProducts = [
    [
        'id' => 101,
        'name' => 'Tech Gadgets Bundle',
        'price' => 199.99,
        'originalPrice' => 249.99,
        'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=300',
        'category' => 'Electronics',
        'discount' => 20
    ],
    [
        'id' => 102,
        'name' => 'Luxury Watch Collection',
        'price' => 599.99,
        'originalPrice' => 699.99,
        'image' => 'https://images.unsplash.com/photo-1523170335258-f5ed11844a49?w=300',
        'category' => 'Fashion',
        'discount' => 15
    ],
    [
        'id' => 103,
        'name' => 'Designer Handbag',
        'price' => 449.99,
        'originalPrice' => 599.99,
        'image' => 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=300',
        'category' => 'Fashion',
        'discount' => 25
    ],
    [
        'id' => 104,
        'name' => 'Collectibles & Art',
        'price' => 299.99,
        'originalPrice' => 329.99,
        'image' => 'https://i.icanvas.com/ic3/horizontal-image/GAK49.jpg?fit=crop&width=435&height=300',
        'category' => 'Collectibles',
        'discount' => 10
    ],
    [
        'id' => 105,
        'name' => 'Home & Garden Tools',
        'price' => 149.99,
        'originalPrice' => 214.99,
        'image' => 'https://images.unsplash.com/photo-1416879595882-3373a0480b5b?w=300',
        'category' => 'Home & Garden',
        'discount' => 30
    ],
    [
        'id' => 106,
        'name' => 'Trading Cards Pack',
        'price' => 89.99,
        'originalPrice' => 94.99,
        'image' => 'https://images.unsplash.com/photo-1612036782180-6f0b6cd846fe?w=300',
        'category' => 'Collectibles',
        'discount' => 5
    ],
    [
        'id' => 107,
        'name' => 'Premium Perfume Set',
        'price' => 179.99,
        'originalPrice' => 224.99,
        'image' => 'https://images.unsplash.com/photo-1541643600914-78b084683601?w=300',
        'category' => 'Beauty',
        'discount' => 20
    ]
];

// Hot Deals with Hot Items
$hotDeals = [
    [
        'id' => 201,
        'name' => 'BlackBerry KEY2 LE - Dual-SIM',
        'price' => 209.21,
        'originalPrice' => 321.99,
        'image' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=300',
        'discount' => 35,
        'hot' => true,
        'category' => 'Electronics'
    ],
    [
        'id' => 202,
        'name' => 'Samsung Galaxy S20 5G 128GB',
        'price' => 599.00,
        'originalPrice' => 799.00,
        'image' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=300',
        'discount' => 25,
        'hot' => false,
        'category' => 'Electronics'
    ],
    [
        'id' => 203,
        'name' => 'Google Pixel 7 Pro 256GB',
        'price' => 899.00,
        'originalPrice' => 1099.00,
        'image' => 'https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=300',
        'discount' => 20,
        'hot' => false,
        'category' => 'Electronics'
    ],
    [
        'id' => 204,
        'name' => 'Dyson Supersonic Hair Dryer',
        'price' => 349.99,
        'originalPrice' => 429.99,
        'image' => 'https://images.unsplash.com/photo-1522338242992-e1a54906a8da?w=300',
        'discount' => 25,
        'hot' => true,
        'category' => 'Beauty'
    ],
    [
        'id' => 205,
        'name' => 'Breville Espresso Machine',
        'price' => 699.99,
        'originalPrice' => 899.99,
        'image' => 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=300',
        'discount' => 25,
        'hot' => true,
        'category' => 'Home & Garden'
    ],
    [
        'id' => 206,
        'name' => 'Sony PlayStation 5 Console',
        'price' => 499.99,
        'originalPrice' => 599.99,
        'image' => 'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=300',
        'discount' => 20,
        'hot' => true,
        'category' => 'Electronics'
    ],
    [
        'id' => 207,
        'name' => 'Apple MacBook Pro M2',
        'price' => 1899.99,
        'originalPrice' => 2299.99,
        'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=300',
        'discount' => 25,
        'hot' => false,
        'category' => 'Electronics'
    ],
    [
        'id' => 208,
        'name' => 'DJI Mini 3 Pro Drone',
        'price' => 759.00,
        'originalPrice' => 899.00,
        'image' => 'https://images.unsplash.com/photo-1473968512647-3e447244af8f?w=300',
        'discount' => 20,
        'hot' => true,
        'category' => 'Electronics'
    ]
];

// Function to get products by category (for filtering)
function getProductsByCategory($products, $category) {
    if ($category === 'all' || empty($category)) {
        return $products;
    }
    return array_filter($products, function($product) use ($category) {
        return $product['category'] === $category;
    });
}

// Function to sort products
function sortProducts($products, $sortBy = 'default') {
    switch ($sortBy) {
        case 'price_low':
            usort($products, function($a, $b) {
                return $a['price'] <=> $b['price'];
            });
            break;
        case 'price_high':
            usort($products, function($a, $b) {
                return $b['price'] <=> $a['price'];
            });
            break;
        case 'name':
            usort($products, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            break;
        default:
            // Keep original order
            break;
    }
    return $products;
}

// Today's Big Deals - Special deals that refresh daily
$todaysBigDeals = [
    [
        'id' => 301,
        'name' => 'Personalized Name Wooden Letters',
        'price' => 4.99,
        'originalPrice' => 9.99,
        'image' => 'https://images.unsplash.com/photo-1513506003901-1e6a229e2d15?w=300',
        'rating' => 4.8,
        'discount' => 50,
        'shipping' => 'Free shipping',
        'expires' => '15:49:30',
        'category' => 'Home & Garden'
    ],
    [
        'id' => 302,
        'name' => 'Custom Canvas Tote Bag - Bridesmaid Gift',
        'price' => 8.89,
        'originalPrice' => 17.99,
        'image' => 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?w=300',
        'rating' => 4.9,
        'discount' => 50,
        'shipping' => 'Free shipping',
        'expires' => '15:49:30',
        'category' => 'Fashion'
    ],
    [
        'id' => 303,
        'name' => 'Musical Wooden Carousel Music Box',
        'price' => 12.99,
        'originalPrice' => 24.99,
        'image' => 'https://i.etsystatic.com/41907320/c/2258/2258/605/0/il/0ce56a/5844691415/il_300x300.5844691415_pruc.jpg',
        'rating' => 4.7,
        'discount' => 48,
        'shipping' => 'Free shipping',
        'expires' => '15:49:30',
        'category' => 'Collectibles'
    ],
    [
        'id' => 304,
        'name' => '18K/18ct Titanium Double Ear Cuff',
        'price' => 17.92,
        'originalPrice' => 29.99,
        'image' => 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=300',
        'rating' => 4.9,
        'discount' => 40,
        'shipping' => 'Express in 2-5 days',
        'expires' => '15:49:30',
        'category' => 'Fashion'
    ],
    [
        'id' => 305,
        'name' => 'Personalized Vegan Leather Toiletry Bag',
        'price' => 4.88,
        'originalPrice' => 12.99,
        'image' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?w=300',
        'rating' => 4.8,
        'discount' => 62,
        'shipping' => 'Express in 3-5 days',
        'expires' => '15:49:30',
        'category' => 'Fashion'
    ],
    [
        'id' => 306,
        'name' => 'Wireless Bluetooth Earbuds Pro',
        'price' => 24.99,
        'originalPrice' => 59.99,
        'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=300',
        'rating' => 4.6,
        'discount' => 58,
        'shipping' => 'Free shipping',
        'expires' => '15:49:30',
        'category' => 'Electronics'
    ]
];

// Editor's Picks Sections
$editorsPicksSections = [
    [
        'id' => 1,
        'title' => 'Kids & Baby Favorites',
        'subtitle' => 'Your one-stop shop for clothing, decor, toys, and more, that all children will love.',
        'buttonText' => 'Shop these unique finds',
        'buttonLink' => 'category.php?cat=kids-baby',
        'products' => [
            [
                'id' => 401,
                'image' => 'https://images.unsplash.com/photo-1519457431-44ccd64a579b?w=300',
                'name' => 'Crochet Bunny Ear Headband',
                'category' => 'Kids & Baby'
            ],
            [
                'id' => 402,
                'image' => 'https://images.unsplash.com/photo-1503342217505-b0a15ec3261c?w=300',
                'name' => 'Personalized Kids Sweatshirt',
                'category' => 'Kids & Baby'
            ],
            [
                'id' => 403,
                'image' => 'https://images.unsplash.com/photo-1514090458221-65bb69cf63e6?w=300',
                'name' => 'Girls Summer Dresses Set',
                'category' => 'Kids & Baby'
            ],
            [
                'id' => 404,
                'image' => 'https://images.unsplash.com/photo-1471286174890-9c112ffca5b4?w=300',
                'name' => 'Boys Casual Outfit',
                'category' => 'Kids & Baby'
            ],
            [
                'id' => 405,
                'image' => 'https://images.unsplash.com/photo-1522771739844-6a9f6d5f14af?w=300',
                'name' => 'Nursery Wall Decor',
                'category' => 'Kids & Baby'
            ],
            [
                'id' => 406,
                'image' => 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?w=300',
                'name' => 'Baby Blanket Set',
                'category' => 'Kids & Baby'
            ]
        ]
    ]
];
?>