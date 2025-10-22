<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../includes/header.php';
require_once 'category-data.php';

// Get category from URL - sanitize and validate
$currentCategory = isset($_GET['category']) ? trim($_GET['category']) : 'all';

// Validate category exists in our categories array
$validCategories = array_merge(['all'], $categories);
if (!in_array($currentCategory, $validCategories)) {
    $currentCategory = 'all';
}

// Filter products by category
$filteredProducts = filterByCategory($products, $currentCategory);

// Get related categories for sidebar
$currentRelated = isset($relatedCategories[$currentCategory]) ? $relatedCategories[$currentCategory] : $relatedCategories['all'];

// Get category description
$categoryDesc = isset($categoryDescriptions[$currentCategory]) ? $categoryDescriptions[$currentCategory] : $categoryDescriptions['all'];

// Get price range for current category
$priceRange = getPriceRange($filteredProducts);

// Get unique brands from filtered products
$categoryBrands = array_unique(array_column($filteredProducts, 'brand'));
sort($categoryBrands);

// Pagination
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$itemsPerPage = 12;
$totalProducts = count($filteredProducts);
$totalPages = max(1, ceil($totalProducts / $itemsPerPage));
$offset = ($page - 1) * $itemsPerPage;
$currentPageProducts = array_slice($filteredProducts, $offset, $itemsPerPage);

// Get recommendations
$bestDeals = getBestDeals($products, 8);
$topRated = getTopRated($products, 8);
$mostPopular = getMostPopular($products, 8);
?>

<link rel="stylesheet" href="../assets/css/category.css">

<main class="category-main">
    <!-- Breadcrumb -->
    <div class="breadcrumb-container">
        <div class="wrapper">
            <nav class="breadcrumb-nav">
                <a href="../index.php" class="breadcrumb-link">Home</a>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <a href="category.php" class="breadcrumb-link">Shop</a>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <strong class="text-dark"><?php echo ucfirst($currentCategory); ?></strong>
            </nav>
        </div>
    </div>

    <!-- Mobile Filter Button (Fixed Bottom) -->
    <button id="filterToggleMobile" class="mobile-filter-btn">
        <i class="fas fa-filter"></i>
        <span>Filters</span>
        <span id="activeFiltersCount" class="filter-count-badge hidden">0</span>
    </button>

    <!-- Filter Overlay -->
    <div id="filterOverlay" class="filter-overlay"></div>

    <div class="content-container">
        <div class="layout-flex">
            <!-- Sidebar -->
            <aside class="sidebar-desktop">
                <div id="filterSidebar" class="filter-sidebar">
                    <!-- Mobile Header -->
                    <div class="sidebar-mobile-header">
                        <h2 class="sidebar-mobile-title">
                            <i class="fas fa-filter"></i>
                            Filters & Categories
                        </h2>
                        <button id="closeSidebar" class="sidebar-close-btn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="sidebar-content">
                        <!-- Categories -->
                        <div class="filter-card">
                            <h3 class="filter-card-title">
                                <i class="fas fa-th-large"></i>
                                Categories
                            </h3>
                            <ul class="category-list">
                                <li><a href="?category=all" class="category-link <?php echo $currentCategory === 'all' ? 'active' : ''; ?>">All Products</a></li>
                                <?php foreach($categories as $cat): ?>
                                    <li><a href="?category=<?php echo urlencode($cat); ?>" class="category-link <?php echo $currentCategory === $cat ? 'active' : ''; ?>"><?php echo htmlspecialchars($cat); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Related Categories -->
                        <div class="filter-card related-card">
                            <h3 class="filter-card-title">
                                <i class="fas fa-link"></i>
                                Related
                            </h3>
                            <div class="filter-options">
                                <?php foreach ($currentRelated as $related): ?>
                                    <a href="?category=<?php echo urlencode($related); ?>" class="related-link">
                                        <i class="fas fa-arrow-right"></i>
                                        <?php echo $related; ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Price Filter -->
                        <div class="filter-card">
                            <h3 class="filter-card-title">
                                <i class="fas fa-dollar-sign"></i>
                                Price Range
                            </h3>
                            <div class="filter-options">
                                <label class="filter-option">
                                    <input type="checkbox" class="price-filter" data-min="0" data-max="100">
                                    <span>Under $100</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" class="price-filter" data-min="100" data-max="300">
                                    <span>$100 - $300</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" class="price-filter" data-min="300" data-max="600">
                                    <span>$300 - $600</span>
                                </label>
                                <label class="filter-option">
                                    <input type="checkbox" class="price-filter" data-min="600" data-max="10000">
                                    <span>$600+</span>
                                </label>
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div class="filter-card">
                            <h3 class="filter-card-title">
                                <i class="fas fa-tags"></i>
                                Brands
                            </h3>
                            <div class="filter-options brand-filter-list">
                                <?php foreach (array_slice($categoryBrands, 0, 10) as $brand): ?>
                                    <label class="filter-option">
                                        <input type="checkbox" class="brand-filter" data-brand="<?php echo htmlspecialchars($brand); ?>">
                                        <span><?php echo htmlspecialchars($brand); ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Shipping Filter -->
                        <div class="filter-card">
                            <label class="filter-option">
                                <input type="checkbox" id="freeShippingOnly">
                                <div>
                                    <div class="filter-title">Free Shipping</div>
                                    <div class="filter-subtitle">Only items with free shipping</div>
                                </div>
                            </label>
                        </div>

                        <!-- Mobile Apply Button -->
                        <div class="mobile-apply-btn">
                            <button id="applyFilters" class="apply-filters-btn">
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-grow">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="page-header-content">
                        <div class="page-header-title">
                            <h1><?php echo ucfirst($currentCategory); ?></h1>
                            <p class="page-header-info">
                                <i class="fas fa-box"></i>
                                <span class="font-semibold"><?php echo number_format($totalProducts); ?></span> products
                            </p>
                        </div>
                        <div class="page-header-badge">
                            <div class="page-header-badge-label">Page</div>
                            <div class="page-header-badge-value"><?php echo $page; ?>/<?php echo $totalPages; ?></div>
                        </div>
                    </div>
                </div>

                <!-- Sort Bar -->
                <div class="sort-bar">
                    <div class="sort-bar-content">
                        <div class="sort-select-wrapper">
                            <label class="sort-label">Sort by</label>
                            <select id="sortSelect" class="sort-select">
                                <option value="relevance">Best Match</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="newest">Newest First</option>
                                <option value="rating">Top Rated</option>
                                <option value="popular">Most Popular</option>
                            </select>
                        </div>

                        <div class="view-toggle-buttons">
                            <button class="view-btn active" data-view="grid">
                                <i class="fas fa-th"></i>
                                <span class="view-btn-text">Grid</span>
                            </button>
                            <button class="view-btn" data-view="list">
                                <i class="fas fa-list"></i>
                                <span class="view-btn-text">List</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div id="productsGrid" class="products-grid">
                    <?php foreach ($currentPageProducts as $product): ?>
                        <div class="product-card" 
                             data-price="<?php echo $product['price']; ?>" 
                             data-rating="<?php echo $product['rating']; ?>"
                             data-reviews="<?php echo $product['reviews']; ?>"
                             data-brand="<?php echo htmlspecialchars($product['brand']); ?>"
                             data-shipping="<?php echo $product['free_shipping'] ? '1' : '0'; ?>"
                             data-id="<?php echo $product['id']; ?>">
                            
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

                            <button class="wishlist-btn" title="Wishlist">
                                <i class="far fa-heart"></i>
                            </button>
                            
                            <div class="product-image-container">
                                <img src="<?php echo $product['image']; ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="product-image"
                                     loading="lazy">
                            </div>
                            
                            <div class="product-info">
                                <div class="product-brand">
                                    <?php echo htmlspecialchars($product['brand']); ?>
                                </div>
                                
                                <h3 class="product-name line-clamp-2">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </h3>
                                
                                <div class="product-rating">
                                    <div class="product-stars">
                                        <?php 
                                        $rating = $product['rating'];
                                        for ($i = 0; $i < 5; $i++) {
                                            echo $i < floor($rating) ? '★' : '☆';
                                        }
                                        ?>
                                    </div>
                                    <span class="product-reviews">(<?php echo number_format($product['reviews']); ?>)</span>
                                </div>
                                
                                <div class="product-price-container">
                                    <span class="product-price">
                                        $<?php echo number_format($product['price'], 2); ?>
                                    </span>
                                    <?php if (!empty($product['old_price'])): ?>
                                        <div class="product-old-price-container">
                                            <span class="product-old-price">
                                                $<?php echo number_format($product['old_price'], 2); ?>
                                            </span>
                                            <span class="product-discount">
                                                -<?php echo round((($product['old_price'] - $product['price']) / $product['old_price']) * 100); ?>%
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($product['free_shipping']): ?>
                                    <div class="free-shipping">
                                        <i class="fas fa-shipping-fast"></i>
                                        <span>Free Ship</span>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="stock-status <?php echo $product['stock'] > 10 ? 'stock-in' : 'stock-low'; ?>">
                                    <?php echo $product['stock'] > 10 ? '<i class="fas fa-check-circle"></i> In Stock' : '<i class="fas fa-exclamation-circle"></i> ' . $product['stock'] . ' left'; ?>
                                </div>

                                <button class="add-to-cart-btn">
                                    <i class="fas fa-shopping-cart"></i>
                                    <span class="mobile-text">Add</span>
                                    <span class="desktop-text">Add to Cart</span>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <div class="pagination-container">
                    <div class="pagination-content">
                        <div class="pagination-info">
                            Showing <span class="pagination-info-highlight"><?php echo $offset + 1; ?></span> to 
                            <span class="pagination-info-highlight"><?php echo min($offset + $itemsPerPage, $totalProducts); ?></span> of 
                            <span class="pagination-info-highlight"><?php echo $totalProducts; ?></span>
                        </div>
                        
                        <div class="pagination-buttons">
                            <?php if ($page > 1): ?>
                                <a href="?category=<?php echo $currentCategory; ?>&page=<?php echo $page - 1; ?>" class="pagination-btn-prev">
                                    <i class="fas fa-chevron-left"></i>
                                    <span class="pagination-btn-text">Prev</span>
                                </a>
                            <?php endif; ?>

                            <div class="pagination-numbers">
                                <?php
                                $startPage = max(1, $page - 1);
                                $endPage = min($totalPages, $page + 1);
                                
                                if ($startPage > 1): ?>
                                    <a href="?category=<?php echo $currentCategory; ?>&page=1" class="pagination-number">1</a>
                                    <?php if ($startPage > 2): ?>
                                        <span class="pagination-ellipsis">...</span>
                                    <?php endif;
                                endif;

                                for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <a href="?category=<?php echo $currentCategory; ?>&page=<?php echo $i; ?>" 
                                       class="pagination-number <?php echo $i === $page ? 'active' : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor;

                                if ($endPage < $totalPages): 
                                    if ($endPage < $totalPages - 1): ?>
                                        <span class="pagination-ellipsis">...</span>
                                    <?php endif; ?>
                                    <a href="?category=<?php echo $currentCategory; ?>&page=<?php echo $totalPages; ?>" class="pagination-number">
                                        <?php echo $totalPages; ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <?php if ($page < $totalPages): ?>
                                <a href="?category=<?php echo $currentCategory; ?>&page=<?php echo $page + 1; ?>" class="pagination-btn-next">
                                    <span class="pagination-btn-text">Next</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script src="../assets/js/category.js"></script>

<?php require_once '../includes/footer.php'; ?>