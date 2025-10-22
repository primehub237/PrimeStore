<?php
// shop/product-detail.php
require_once '../includes/header.php';
require_once 'product-detail-data.php';

// Get product ID from URL
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Find the product
$product = null;
foreach ($productDetails as $p) {
    if ($p['id'] === $productId) {
        $product = $p;
        break;
    }
}

// If product not found, redirect or show error
if (!$product) {
    header('Location: products.php');
    exit;
}

// Get related products (you can customize this logic)
require_once 'product-data.php';
$similarProducts = array_slice($products, 0, 8);
$exploreProducts = array_slice($products, 4, 8); // Some overlap or different set
?>

<!-- Link to external CSS -->
<link rel="stylesheet" href="../assets/css/product-detail.css">

<main class="main-container">
    <!-- Breadcrumb -->
    <div class="breadcrumb-container">
        <div class="wrapper">
            <nav class="breadcrumb-nav">
                <a href="../index.php" class="breadcrumb-link">Home</a>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <a href="products.php" class="breadcrumb-link">Shop</a>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <a href="products.php?category=<?php echo urlencode($product['category']); ?>" class="breadcrumb-link">
                    <?php echo htmlspecialchars($product['category']); ?>
                </a>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <strong class="text-dark"><?php echo htmlspecialchars($product['name']); ?></strong>
            </nav>
        </div>
    </div>

    <div class="content-container">
        <!-- Product Main Section -->
        <div class="product-detail-layout">
            <!-- Left: Image Gallery -->
            <div class="product-gallery">
                <!-- Main Image -->
                <div class="main-image-container">
                    <?php if (!empty($product['badge'])): ?>
                        <div class="product-badge">
                            <span class="<?php 
                                echo strtolower($product['badge']) === 'new' ? 'badge-new' : 
                                    (strtolower($product['badge']) === 'sale' ? 'badge-sale' : 'badge-hot'); 
                            ?>">
                                <?php echo $product['badge']; ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <button class="wishlist-btn-large" title="Add to Wishlist">
                        <i class="far fa-heart"></i>
                    </button>

                    <div class="main-image-wrapper">
                        <img id="mainImage" src="<?php echo $product['images'][0]; ?>" 
                             alt="<?php echo htmlspecialchars($product['name']); ?>" 
                             class="main-image">
                        <button class="zoom-btn" id="zoomBtn">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Thumbnail Gallery -->
                <div class="thumbnail-gallery">
                    <?php foreach ($product['images'] as $index => $image): ?>
                        <div class="thumbnail-item <?php echo $index === 0 ? 'active' : ''; ?>" 
                             data-image="<?php echo $image; ?>">
                            <img src="<?php echo $image; ?>" 
                                 alt="Product view <?php echo $index + 1; ?>" 
                                 class="thumbnail-image">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Right: Product Info -->
            <div class="product-info-section">
                <!-- Brand -->
                <div class="product-brand-badge">
                    <i class="fas fa-award"></i>
                    <?php echo htmlspecialchars($product['brand']); ?>
                </div>

                <!-- Product Name -->
                <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>

                <!-- Rating & Reviews -->
                <div class="rating-section">
                    <div class="product-stars-large">
                        <?php 
                        $rating = $product['rating'];
                        for ($i = 0; $i < 5; $i++) {
                            echo $i < floor($rating) ? '★' : '☆';
                        }
                        ?>
                    </div>
                    <span class="rating-value"><?php echo $rating; ?></span>
                    <span class="review-count">(<?php echo number_format($product['reviews']); ?> reviews)</span>
                    <a href="#reviews" class="write-review-link">Write a review</a>
                </div>

                <!-- Price -->
                <div class="price-section">
                    <div class="price-main">
                        <span class="current-price">$<?php echo number_format($product['price'], 2); ?></span>
                        <?php if (!empty($product['old_price'])): ?>
                            <span class="old-price">$<?php echo number_format($product['old_price'], 2); ?></span>
                            <span class="discount-badge">
                                -<?php echo round((($product['old_price'] - $product['price']) / $product['old_price']) * 100); ?>% OFF
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php if ($product['free_shipping']): ?>
                        <div class="shipping-badge">
                            <i class="fas fa-shipping-fast"></i>
                            <span>Free Shipping</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Stock Status -->
                <div class="stock-section">
                    <div class="stock-status <?php echo $product['stock'] > 10 ? 'stock-in' : 'stock-low'; ?>">
                        <i class="fas fa-<?php echo $product['stock'] > 10 ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                        <?php 
                        if ($product['stock'] > 10) {
                            echo 'In Stock - Ready to Ship';
                        } else {
                            echo 'Only ' . $product['stock'] . ' left in stock!';
                        }
                        ?>
                    </div>
                </div>

                <!-- Key Features -->
                <div class="features-quick">
                    <h3 class="features-title">Key Features:</h3>
                    <ul class="features-list">
                        <?php foreach (array_slice($product['features'], 0, 4) as $feature): ?>
                            <li class="feature-item">
                                <i class="fas fa-check-circle"></i>
                                <span><?php echo htmlspecialchars($feature); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Quantity Selector -->
                <div class="quantity-section">
                    <label class="quantity-label">Quantity:</label>
                    <div class="quantity-controls">
                        <button class="quantity-btn" id="decreaseQty">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" id="quantityInput" value="1" min="1" max="<?php echo $product['stock']; ?>" class="quantity-input">
                        <button class="quantity-btn" id="increaseQty">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <span class="quantity-info"><?php echo $product['stock']; ?> available</span>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <button class="btn-add-to-cart" id="addToCartBtn">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Add to Cart</span>
                    </button>
                    <button class="btn-buy-now" id="buyNowBtn">
                        <i class="fas fa-bolt"></i>
                        <span>Buy Now</span>
                    </button>
                </div>

                <!-- Additional Info -->
                <div class="additional-info">
                    <div class="info-item">
                        <i class="fas fa-shield-alt"></i>
                        <div class="info-content">
                            <strong>Secure Payment</strong>
                            <span>100% secure payment</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-undo-alt"></i>
                        <div class="info-content">
                            <strong>Easy Returns</strong>
                            <span>30-day return policy</span>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-headset"></i>
                        <div class="info-content">
                            <strong>24/7 Support</strong>
                            <span>Dedicated support</span>
                        </div>
                    </div>
                </div>

                <!-- Share -->
                <div class="share-section">
                    <span class="share-label">Share:</span>
                    <div class="share-buttons">
                        <button class="share-btn" title="Share on Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button class="share-btn" title="Share on Twitter">
                            <i class="fab fa-twitter"></i>
                        </button>
                        <button class="share-btn" title="Share on WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                        <button class="share-btn" title="Copy Link">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Similar Products -->
        <section class="similar-products-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-layer-group"></i>
                    Similar Products
                </h2>
                <a href="products.php?category=<?php echo urlencode($product['category']); ?>" class="view-all-link">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="scroll-wrapper">
                <div class="horizontal-scroll-container">
                    <?php 
                    foreach ($similarProducts as $relatedProduct): 
                    ?>
                        <div class="product-card" 
                             data-price="<?php echo $relatedProduct['price']; ?>" 
                             data-id="<?php echo $relatedProduct['id']; ?>">
                            
                            <?php if (!empty($relatedProduct['badge'])): ?>
                                <div class="product-badge">
                                    <span class="<?php 
                                        echo strtolower($relatedProduct['badge']) === 'new' ? 'badge-new' : 
                                            (strtolower($relatedProduct['badge']) === 'sale' ? 'badge-sale' : 'badge-hot'); 
                                    ?>">
                                        <?php echo $relatedProduct['badge']; ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <button class="wishlist-btn" title="Wishlist">
                                <i class="far fa-heart"></i>
                            </button>
                            
                            <div class="product-image-container">
                                <img src="<?php echo $relatedProduct['image']; ?>" 
                                     alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>" 
                                     class="product-image">
                            </div>
                            
                            <div class="product-info">
                                <div class="product-brand">
                                    <?php echo htmlspecialchars($relatedProduct['brand']); ?>
                                </div>
                                
                                <h3 class="product-name line-clamp-2">
                                    <?php echo htmlspecialchars($relatedProduct['name']); ?>
                                </h3>
                                
                                <div class="product-rating">
                                    <div class="product-stars">
                                        <?php 
                                        $rating = $relatedProduct['rating'];
                                        for ($i = 0; $i < 5; $i++) {
                                            echo $i < floor($rating) ? '★' : '☆';
                                        }
                                        ?>
                                    </div>
                                    <span class="product-reviews">(<?php echo number_format($relatedProduct['reviews']); ?>)</span>
                                </div>
                                
                                <div class="product-price-container">
                                    <span class="product-price">
                                        $<?php echo number_format($relatedProduct['price'], 2); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="scroll-btn left"><i class="fas fa-chevron-left"></i></button>
                <button class="scroll-btn right"><i class="fas fa-chevron-right"></i></button>
            </div>
        </section>


        <!-- Product Details Tabs -->
        <div class="product-tabs-section mt-20">
            <div class="tabs-header">
                <button class="tab-btn active" data-tab="description">
                    <i class="fas fa-info-circle"></i>
                    Description
                </button>
                <button class="tab-btn" data-tab="specifications">
                    <i class="fas fa-list-ul"></i>
                    Specifications
                </button>
                <button class="tab-btn" data-tab="reviews">
                    <i class="fas fa-star"></i>
                    Reviews (<?php echo number_format($product['reviews']); ?>)
                </button>
                <button class="tab-btn" data-tab="seller">
                    <i class="fas fa-user"></i>
                    Seller
                </button>
            </div>

            <div class="tabs-content">
                <!-- Description Tab -->
                <div class="tab-panel active" id="description">
                    <h2 class="tab-panel-title">About This Product</h2>
                    <p class="product-description"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    
                    <h3 class="features-section-title">Complete Features List</h3>
                    <ul class="features-list-detailed">
                        <?php foreach ($product['features'] as $feature): ?>
                            <li class="feature-item-detailed">
                                <i class="fas fa-check-circle"></i>
                                <span><?php echo htmlspecialchars($feature); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Product Images Gallery -->
                    <div class="product-images-showcase">
                        <?php foreach ($product['images'] as $image): ?>
                            <div class="showcase-image-container">
                                <img src="<?php echo $image; ?>" alt="Product showcase" class="showcase-image">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Specifications Tab -->
                <div class="tab-panel" id="specifications">
                    <h2 class="tab-panel-title">Technical Specifications</h2>
                    <div class="specs-table">
                        <?php foreach ($product['specifications'] as $key => $value): ?>
                            <div class="spec-row">
                                <div class="spec-label"><?php echo htmlspecialchars($key); ?></div>
                                <div class="spec-value"><?php echo htmlspecialchars($value); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Reviews Tab -->
                <div class="tab-panel" id="reviews">
                    <h2 class="tab-panel-title">Customer Reviews</h2>
                    
                    <div class="reviews-summary">
                        <div class="reviews-summary-left">
                            <div class="overall-rating"><?php echo $rating; ?></div>
                            <div class="product-stars-large">
                                <?php 
                                for ($i = 0; $i < 5; $i++) {
                                    echo $i < floor($rating) ? '★' : '☆';
                                }
                                ?>
                            </div>
                            <div class="reviews-count"><?php echo number_format($product['reviews']); ?> reviews</div>
                        </div>
                        <div class="reviews-summary-right">
                            <?php
                            $ratingDistribution = [5 => 65, 4 => 20, 3 => 10, 2 => 3, 1 => 2];
                            foreach ($ratingDistribution as $stars => $percentage):
                            ?>
                                <div class="rating-bar-row">
                                    <span class="rating-bar-label"><?php echo $stars; ?>★</span>
                                    <div class="rating-bar">
                                        <div class="rating-bar-fill" style="width: <?php echo $percentage; ?>%"></div>
                                    </div>
                                    <span class="rating-bar-percent"><?php echo $percentage; ?>%</span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <button class="btn-write-review">
                        <i class="fas fa-pen"></i>
                        Write a Review
                    </button>

                    <!-- Sample Reviews -->
                    <div class="reviews-list">
                        <?php
                        $sampleReviews = [
                            ['name' => 'John D.', 'rating' => 5, 'date' => '2 days ago', 'comment' => 'Excellent product! Exceeded my expectations. Fast shipping and great quality.'],
                            ['name' => 'Sarah M.', 'rating' => 5, 'date' => '1 week ago', 'comment' => 'Very happy with this purchase. Works perfectly and arrived on time.'],
                            ['name' => 'Mike R.', 'rating' => 4, 'date' => '2 weeks ago', 'comment' => 'Good product overall. Slight delay in shipping but worth the wait.'],
                        ];
                        
                        foreach ($sampleReviews as $review):
                        ?>
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="review-avatar">
                                        <?php echo strtoupper(substr($review['name'], 0, 1)); ?>
                                    </div>
                                    <div class="review-meta">
                                        <div class="review-author"><?php echo htmlspecialchars($review['name']); ?></div>
                                        <div class="review-stars">
                                            <?php 
                                            for ($i = 0; $i < 5; $i++) {
                                                echo $i < $review['rating'] ? '★' : '☆';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="review-date"><?php echo $review['date']; ?></div>
                                </div>
                                <p class="review-comment"><?php echo htmlspecialchars($review['comment']); ?></p>
                                <div class="review-actions">
                                    <button class="review-action-btn">
                                        <i class="far fa-thumbs-up"></i> Helpful
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Seller Tab -->
                <div class="tab-panel" id="seller">
                    <h2 class="tab-panel-title">About This Seller</h2>
                    
                    <!-- Seller Overview -->
                    <div class="seller-overview" style="display: flex; flex-wrap: wrap; gap: 2rem; margin-bottom: 2rem; background: #f9f9f9; padding: 1.5rem; border: 2px solid var(--gold-light); border-radius: 12px;">
                        <div class="seller-profile" style="flex: 1 1 300px; display: flex; align-items: center; gap: 1rem;">
                            <img src="https://via.placeholder.com/100?text=Swing" alt="Seller Logo" style="width: 80px; height: 80px; border-radius: 50%; border: 2px solid var(--gold-primary);">
                            <div>
                                <h3 style="font-size: 1.5rem; color: var(--text-dark); margin-bottom: 0.5rem;">Swing Computers</h3>
                                <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--success);">
                                    <i class="fas fa-star"></i>
                                    <span>99.3% Positive feedback</span>
                                </div>
                                <span style="color: var(--text-light);">307K items sold</span>
                            </div>
                        </div>
                        
                        <div class="seller-stats" style="flex: 1 1 300px;">
                            <div style="margin-bottom: 0.5rem;"><i class="fas fa-calendar-alt" style="color: var(--gold-primary);"></i> Joined May 2010</div>
                            <div style="margin-bottom: 0.5rem;"><i class="fas fa-clock" style="color: var(--gold-primary);"></i> Usually responds within 24 hours</div>
                            <div class="seller-actions" style="display: flex; gap: 1rem; margin-top: 1rem;">
                                <a href="#" class="btn" style="background: var(--gold-primary); color: var(--white); padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none;">Visit store</a>
                                <a href="#" class="btn" style="border: 2px solid var(--gold-primary); color: var(--gold-primary); padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none;">Contact</a>
                                <button class="btn" style="border: 2px solid var(--gold-primary); color: var(--gold-primary); padding: 0.5rem 1rem; border-radius: 6px;">Save seller</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detailed Ratings -->
                    <div class="detailed-ratings" style="margin-bottom: 2rem;">
                        <h3 class="features-section-title">Detailed Seller Ratings</h3>
                        <p style="color: var(--text-light); margin-bottom: 1rem;">Average for the last 12 months</p>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                            <div class="rating-item" style="display: flex; justify-content: space-between; padding: 0.75rem; background: #f9f9f9; border: 2px solid var(--border-color); border-radius: 8px;">
                                <span>Accurate description</span>
                                <span style="font-weight: bold; color: var(--gold-primary);">4.9</span>
                            </div>
                            <div class="rating-item" style="display: flex; justify-content: space-between; padding: 0.75rem; background: #f9f9f9; border: 2px solid var(--border-color); border-radius: 8px;">
                                <span>Reasonable shipping cost</span>
                                <span style="font-weight: bold; color: var(--gold-primary);">4.8</span>
                            </div>
                            <div class="rating-item" style="display: flex; justify-content: space-between; padding: 0.75rem; background: #f9f9f9; border: 2px solid var(--border-color); border-radius: 8px;">
                                <span>Shipping speed</span>
                                <span style="font-weight: bold; color: var(--gold-primary);">5.0</span>
                            </div>
                            <div class="rating-item" style="display: flex; justify-content: space-between; padding: 0.75rem; background: #f9f9f9; border: 2px solid var(--border-color); border-radius: 8px;">
                                <span>Communication</span>
                                <span style="font-weight: bold; color: var(--gold-primary);">5.0</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Popular Categories -->
                    <div class="popular-categories" style="margin-bottom: 2rem;">
                        <h3 class="features-section-title">Popular Categories from This Store</h3>
                        <ul style="list-style: none; display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 0.5rem;">
                            <li><a href="#" style="color: var(--gold-primary); text-decoration: none;">Collectibles</a></li>
                            <li><a href="#" style="color: var(--gold-primary); text-decoration: none;">Crafts</a></li>
                            <li><a href="#" style="color: var(--gold-primary); text-decoration: none;">Travel</a></li>
                            <li><a href="#" style="color: var(--gold-primary); text-decoration: none;">Clothing, Shoes & Accessories</a></li>
                            <li><a href="#" style="color: var(--gold-primary); text-decoration: none;">Toys & Hobbies</a></li>
                            <li><a href="#" style="color: var(--gold-primary); text-decoration: none;">Video Games & Consoles</a></li>
                            <li><a href="#" style="color: var(--gold-primary); text-decoration: none;">Jewelry & Watches</a></li>
                            <li><a href="#" style="color: var(--gold-primary); text-decoration: none;">Business & Industrial</a></li>
                            <li><a href="#" style="color: var(--gold-primary); text-decoration: none;">Consumer Electronics</a></li>
                        </ul>
                    </div>
                    
                    <!-- Seller Feedback -->
                    <div class="seller-feedback">
                        <h3 class="features-section-title">Seller Feedback (4,402)</h3>
                        <div class="reviews-list">
                            <!-- Sample Feedback 1 -->
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="review-avatar">A</div>
                                    <div class="review-meta">
                                        <div class="review-author">Anonymous</div>
                                        <div class="review-stars">★★★★★</div>
                                    </div>
                                    <div class="review-date">Oct 15, 2024</div>
                                </div>
                                <p class="review-comment">Quick shipping and very well packaged. They made sure it arrived quickly and safe. The item is exactly as described. Great value for money. Verified purchase</p>
                                <div class="review-actions">
                                    <button class="review-action-btn"><i class="far fa-thumbs-up"></i> Helpful</button>
                                </div>
                            </div>
                            
                            <!-- Sample Feedback 2 -->
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="review-avatar">B</div>
                                    <div class="review-meta">
                                        <div class="review-author">Buyer123</div>
                                        <div class="review-stars">★★★★★</div>
                                    </div>
                                    <div class="review-date">Oct 10, 2024</div>
                                </div>
                                <p class="review-comment">Thank you for your detailed feedback. We're glad you're happy with the shipping, packaging, and product quality. Sorry again. LHO-Ovid-PVMT-5 1P-UFTA-1 UFSI-5010-5010-HVMT-FFR020524H4</p>
                                <div class="review-actions">
                                    <button class="review-action-btn"><i class="far fa-thumbs-up"></i> Helpful</button>
                                </div>
                            </div>
                            
                            <!-- Add more feedbacks as needed -->
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="review-avatar">C</div>
                                    <div class="review-meta">
                                        <div class="review-author">CustomerX</div>
                                        <div class="review-stars">★★★★★</div>
                                    </div>
                                    <div class="review-date">Oct 5, 2024</div>
                                </div>
                                <p class="review-comment">One of the best I've ever seen. They were quick to respond to my packaging missing the individual power adapter. The charger was...</p>
                                <div class="review-actions">
                                    <button class="review-action-btn"><i class="far fa-thumbs-up"></i> Helpful</button>
                                </div>
                            </div>
                            
                            <div class="review-item">
                                <div class="review-header">
                                    <div class="review-avatar">D</div>
                                    <div class="review-meta">
                                        <div class="review-author">ShopperY</div>
                                        <div class="review-stars">★★★☆☆</div>
                                    </div>
                                    <div class="review-date">Sep 30, 2024</div>
                                </div>
                                <p class="review-comment">Shipped within 4 days. Packaged so secure. Product exactly as described. Charger wasn't included but communication was excellent and they shipped out the charger at no cost.</p>
                                <div class="review-actions">
                                    <button class="review-action-btn"><i class="far fa-thumbs-up"></i> Helpful</button>
                                </div>
                            </div>
                            
                            <!-- More if needed -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Explore Related Items -->
        <section class="explore-related-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-compass"></i>
                    Explore Related Items
                </h2>
                <a href="products.php" class="view-all-link">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="scroll-wrapper">
                <div class="horizontal-scroll-container">
                    <?php 
                    foreach ($exploreProducts as $exploreProduct): 
                    ?>
                        <div class="product-card" 
                             data-price="<?php echo $exploreProduct['price']; ?>" 
                             data-id="<?php echo $exploreProduct['id']; ?>">
                            
                            <?php if (!empty($exploreProduct['badge'])): ?>
                                <div class="product-badge">
                                    <span class="<?php 
                                        echo strtolower($exploreProduct['badge']) === 'new' ? 'badge-new' : 
                                            (strtolower($exploreProduct['badge']) === 'sale' ? 'badge-sale' : 'badge-hot'); 
                                    ?>">
                                        <?php echo $exploreProduct['badge']; ?>
                                    </span>
                                </div>
                            <?php endif; ?>

                            <button class="wishlist-btn" title="Wishlist">
                                <i class="far fa-heart"></i>
                            </button>
                            
                            <div class="product-image-container">
                                <img src="<?php echo $exploreProduct['image']; ?>" 
                                     alt="<?php echo htmlspecialchars($exploreProduct['name']); ?>" 
                                     class="product-image">
                            </div>
                            
                            <div class="product-info">
                                <div class="product-brand">
                                    <?php echo htmlspecialchars($exploreProduct['brand']); ?>
                                </div>
                                
                                <h3 class="product-name line-clamp-2">
                                    <?php echo htmlspecialchars($exploreProduct['name']); ?>
                                </h3>
                                
                                <div class="product-rating">
                                    <div class="product-stars">
                                        <?php 
                                        $rating = $exploreProduct['rating'];
                                        for ($i = 0; $i < 5; $i++) {
                                            echo $i < floor($rating) ? '★' : '☆';
                                        }
                                        ?>
                                    </div>
                                    <span class="product-reviews">(<?php echo number_format($exploreProduct['reviews']); ?>)</span>
                                </div>
                                
                                <div class="product-price-container">
                                    <span class="product-price">
                                        $<?php echo number_format($exploreProduct['price'], 2); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="scroll-btn left"><i class="fas fa-chevron-left"></i></button>
                <button class="scroll-btn right"><i class="fas fa-chevron-right"></i></button>
            </div>
        </section>
    
       <!-- Goods You May Also Like Section -->
        <section class="goods-you-may-like-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-heart"></i>
                    Goods You May Also Like
                </h2>
                <a href="products.php" class="view-all-link">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            
            <div class="goods-grid">
                <?php 
                // Assuming $products array from product-data.php contains all products
                // Select a subset of products (e.g., 8 different ones)
                $goodsYouMayLike = array_slice($products, 2, 8); // Offset by 2 to avoid overlap with similar/explore products
                foreach ($goodsYouMayLike as $relatedProduct): 
                ?>
                    <div class="product-card w-100" 
                        data-price="<?php echo $relatedProduct['price']; ?>" 
                        data-id="<?php echo $relatedProduct['id']; ?>">
                        
                        <?php if (!empty($relatedProduct['badge'])): ?>
                            <div class="product-badge">
                                <span class="<?php 
                                    echo strtolower($relatedProduct['badge']) === 'new' ? 'badge-new' : 
                                        (strtolower($relatedProduct['badge']) === 'sale' ? 'badge-sale' : 'badge-hot'); 
                                ?>">
                                    <?php echo $relatedProduct['badge']; ?>
                                </span>
                            </div>
                        <?php endif; ?>

                        <button class="wishlist-btn" title="Wishlist">
                            <i class="far fa-heart"></i>
                        </button>
                        
                        <div class="product-image-container">
                            <img src="<?php echo $relatedProduct['image']; ?>" 
                                alt="<?php echo htmlspecialchars($relatedProduct['name']); ?>" 
                                class="product-image">
                        </div>
                        
                        <div class="product-info">
                            <div class="product-brand">
                                <?php echo htmlspecialchars($relatedProduct['brand']); ?>
                            </div>
                            
                            <h3 class="product-name line-clamp-2">
                                <?php echo htmlspecialchars($relatedProduct['name']); ?>
                            </h3>
                            
                            <div class="product-rating">
                                <div class="product-stars">
                                    <?php 
                                    $rating = $relatedProduct['rating'];
                                    for ($i = 0; $i < 5; $i++) {
                                        echo $i < floor($rating) ? '★' : '☆';
                                    }
                                    ?>
                                </div>
                                <span class="product-reviews">(<?php echo number_format($relatedProduct['reviews']); ?>)</span>
                            </div>
                            
                            <div class="product-price-container">
                                <span class="product-price">
                                    $<?php echo number_format($relatedProduct['price'], 2); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
    
</main>

<!-- Image Zoom Modal -->
<div id="imageZoomModal" class="zoom-modal">
    <button class="zoom-modal-close">
        <i class="fas fa-times"></i>
    </button>
    <img id="zoomModalImage" src="" alt="Zoomed product" class="zoom-modal-image">
</div>

<script src="../assets/js/product-detail.js"></script>

<?php require_once '../includes/footer.php'; ?>