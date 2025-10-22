<?php
// shop/products.php
require_once '../includes/header.php';
require_once 'product-data.php';

// Get category from URL parameter
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$itemsPerPage = 24;

// Filter products by category
$filteredProducts = $category === 'all' ? $products : array_filter($products, function($product) use ($category) {
    return strtolower($product['category']) === strtolower($category);
});

// Pagination
$totalProducts = count($filteredProducts);
$totalPages = ceil($totalProducts / $itemsPerPage);
$offset = ($page - 1) * $itemsPerPage;
$currentProducts = array_slice($filteredProducts, $offset, $itemsPerPage);

// Get unique brands for filter
$brands = array_unique(array_column($filteredProducts, 'brand'));
sort($brands);

// Related categories
$relatedCategories = [
    'Processors' => ['Motherboards', 'Cooling', 'Cases'],
    'Graphics Cards' => ['Monitors', 'Power Supplies', 'Cases'],
    'Memory' => ['Motherboards', 'Storage', 'Processors'],
    'Storage' => ['Cases', 'Cables', 'Memory'],
    'Monitors' => ['Graphics Cards', 'Keyboards', 'Mice'],
    'Keyboards' => ['Mice', 'Headsets', 'Monitors'],
    'Mice' => ['Keyboards', 'Headsets', 'Monitors']
];

$currentRelated = isset($relatedCategories[$category]) ? $relatedCategories[$category] : ['Processors', 'Memory', 'Storage'];

// Get random products for recommendations
$mayAlsoLike = array_slice($products, 0, 15);
shuffle($mayAlsoLike);

// Get best selling products
$bestSelling = $products;
usort($bestSelling, function($a, $b) {
    return $b['reviews'] - $a['reviews'];
});
$bestSelling = array_slice($bestSelling, 0, 15);
?>

<!-- Link to external CSS -->
<link rel="stylesheet" href="../assets/css/products.css">

<main class="main-container">
    <!-- Breadcrumb -->
    <div class="breadcrumb-container">
        <div class="wrapper">
            <nav class="breadcrumb-nav">
                <a href="../index.php" class="breadcrumb-link">Home</a>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <a href="category.php" class="breadcrumb-link">Shop</a>
                <i class="fas fa-chevron-right breadcrumb-separator"></i>
                <strong class="text-dark"><?php echo ucfirst($category); ?></strong>
            </nav>
        </div>
    </div>

    <div class="content-container">
        <div class="layout-flex">
            <!-- Mobile Filter Button -->
            <button id="filterToggleMobile" class="mobile-filter-btn">
                <i class="fas fa-filter"></i>
                <span>Filters</span>
                <span id="activeFiltersCount" class="filter-count-badge hidden">0</span>
            </button>

            <!-- Filter Overlay -->
            <div id="filterOverlay" class="filter-overlay"></div>
            
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
                                <li><a href="?category=all" class="category-link <?php echo $category === 'all' ? 'active' : ''; ?>">All Products</a></li>
                                <li><a href="?category=Processors" class="category-link <?php echo $category === 'Processors' ? 'active' : ''; ?>">Processors</a></li>
                                <li><a href="?category=Graphics Cards" class="category-link <?php echo $category === 'Graphics Cards' ? 'active' : ''; ?>">Graphics Cards</a></li>
                                <li><a href="?category=Motherboards" class="category-link <?php echo $category === 'Motherboards' ? 'active' : ''; ?>">Motherboards</a></li>
                                <li><a href="?category=Memory" class="category-link <?php echo $category === 'Memory' ? 'active' : ''; ?>">Memory (RAM)</a></li>
                                <li><a href="?category=Storage" class="category-link <?php echo $category === 'Storage' ? 'active' : ''; ?>">Storage</a></li>
                                <li><a href="?category=Monitors" class="category-link <?php echo $category === 'Monitors' ? 'active' : ''; ?>">Monitors</a></li>
                                <li><a href="?category=Keyboards" class="category-link <?php echo $category === 'Keyboards' ? 'active' : ''; ?>">Keyboards</a></li>
                                <li><a href="?category=Mice" class="category-link <?php echo $category === 'Mice' ? 'active' : ''; ?>">Mice</a></li>
                                <li><a href="?category=Cooling" class="category-link <?php echo $category === 'Cooling' ? 'active' : ''; ?>">Cooling</a></li>
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
                                <?php foreach (array_slice($brands, 0, 10) as $brand): ?>
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
                            <h1><?php echo ucfirst($category); ?></h1>
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
                    <?php foreach ($currentProducts as $product): ?>
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
                                     class="product-image">
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
                                <a href="?category=<?php echo $category; ?>&page=<?php echo $page - 1; ?>" class="pagination-btn-prev">
                                    <i class="fas fa-chevron-left"></i>
                                    <span class="pagination-btn-text">Prev</span>
                                </a>
                            <?php endif; ?>

                            <div class="pagination-numbers">
                                <?php
                                $startPage = max(1, $page - 1);
                                $endPage = min($totalPages, $page + 1);
                                
                                if ($startPage > 1): ?>
                                    <a href="?category=<?php echo $category; ?>&page=1" class="pagination-number">1</a>
                                    <?php if ($startPage > 2): ?>
                                        <span class="pagination-ellipsis">...</span>
                                    <?php endif;
                                endif;

                                for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <a href="?category=<?php echo $category; ?>&page=<?php echo $i; ?>" 
                                       class="pagination-number <?php echo $i === $page ? 'active' : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                <?php endfor;

                                if ($endPage < $totalPages): 
                                    if ($endPage < $totalPages - 1): ?>
                                        <span class="pagination-ellipsis">...</span>
                                    <?php endif; ?>
                                    <a href="?category=<?php echo $category; ?>&page=<?php echo $totalPages; ?>" class="pagination-number">
                                        <?php echo $totalPages; ?>
                                    </a>
                                <?php endif; ?>
                            </div>

                            <?php if ($page < $totalPages): ?>
                                <a href="?category=<?php echo $category; ?>&page=<?php echo $page + 1; ?>" class="pagination-btn-next">
                                    <span class="pagination-btn-text">Next</span>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- You May Also Like -->
                <section class="recommendation-section">
                    <div class="recommendation-header">
                        <h2 class="recommendation-title">
                            <i class="fas fa-heart icon-may-like"></i>
                            <span>You may also like</span>
                        </h2>
                        <div class="recommendation-scroll-buttons">
                            <button id="alsoLikeScrollLeft" class="scroll-button">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button id="alsoLikeScrollRight" class="scroll-button">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div id="alsoLikeContainer" class="recommendation-container scrollbar-hide">
                        <?php foreach ($mayAlsoLike as $product): ?>
                            <div class="recommendation-card">
                                <div class="recommendation-image-container">
                                    <img src="<?php echo $product['image']; ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                         class="recommendation-image">
                                </div>
                                <div class="recommendation-info">
                                    <div class="recommendation-brand">
                                        <?php echo htmlspecialchars($product['brand']); ?>
                                    </div>
                                    <h3 class="recommendation-name line-clamp-2">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </h3>
                                    <div class="recommendation-price">
                                        $<?php echo number_format($product['price'], 2); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Best Selling -->
                <section class="recommendation-section">
                    <div class="recommendation-header">
                        <h2 class="recommendation-title">
                            <i class="fas fa-fire icon-best-selling"></i>
                            <span>Best Selling</span>
                        </h2>
                        <div class="recommendation-scroll-buttons">
                            <button id="bestSellingScrollLeft" class="scroll-button">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button id="bestSellingScrollRight" class="scroll-button">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div id="bestSellingContainer" class="recommendation-container scrollbar-hide">
                        <?php foreach ($bestSelling as $product): ?>
                            <div class="recommendation-card">
                                <?php if ($product['reviews'] > 3000): ?>
                                    <div class="product-badge">
                                        <span class="badge-sale">
                                            <i class="fas fa-fire"></i> HOT
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <div class="recommendation-image-container">
                                    <img src="<?php echo $product['image']; ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                         class="recommendation-image">
                                </div>
                                <div class="recommendation-info">
                                    <div class="recommendation-brand">
                                        <?php echo htmlspecialchars($product['brand']); ?>
                                    </div>
                                    <h3 class="recommendation-name line-clamp-2">
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
                                    <div class="recommendation-price">
                                        $<?php echo number_format($product['price'], 2); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Trending Now -->
                <section class="recommendation-section trending-section">
                    <div class="recommendation-header">
                        <h2 class="recommendation-title trending-title">
                            <i class="fas fa-chart-line"></i>
                            <span>Trending Now</span>
                        </h2>
                        <div class="recommendation-scroll-buttons">
                            <button id="trendingScrollLeft" class="scroll-button trending-scroll-button">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button id="trendingScrollRight" class="scroll-button trending-scroll-button">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div id="trendingContainer" class="recommendation-container trending-container scrollbar-hide">
                        <?php 
                        $trending = array_slice($products, 10, 15);
                        foreach ($trending as $product): ?>
                            <div class="recommendation-card trending-card">
                                <div class="recommendation-image-container">
                                    <img src="<?php echo $product['image']; ?>" 
                                         alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                         class="recommendation-image">
                                </div>
                                <div class="recommendation-info">
                                    <div class="recommendation-brand">
                                        <?php echo htmlspecialchars($product['brand']); ?>
                                    </div>
                                    <h3 class="recommendation-name line-clamp-2">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </h3>
                                    <div class="recommendation-price">
                                        $<?php echo number_format($product['price'], 2); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- CTA Banner -->
                <div class="cta-banner">
                    <h2 class="cta-title">Can't find what you need?</h2>
                    <p class="cta-subtitle">Browse our catalog or contact support</p>
                    <div class="cta-buttons">
                        <button class="cta-btn-primary">
                            <i class="fas fa-search"></i> Browse Categories
                        </button>
                        <button class="cta-btn-secondary">
                            <i class="fas fa-headset"></i> Contact Support
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
// Mobile filter toggle
const filterToggleMobile = document.getElementById('filterToggleMobile');
const filterSidebar = document.getElementById('filterSidebar');
const filterOverlay = document.getElementById('filterOverlay');
const closeSidebar = document.getElementById('closeSidebar');
const applyFilters = document.getElementById('applyFilters');

function openFilters() {
    filterSidebar.classList.add('active');
    filterOverlay.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeFilters() {
    filterSidebar.classList.remove('active');
    filterOverlay.classList.remove('active');
    document.body.style.overflow = '';
}

filterToggleMobile?.addEventListener('click', openFilters);
filterOverlay?.addEventListener('click', closeFilters);
closeSidebar?.addEventListener('click', closeFilters);
applyFilters?.addEventListener('click', closeFilters);

// Update active filter count
function updateFilterCount() {
    const activeFilters = document.querySelectorAll('.price-filter:checked, .brand-filter:checked').length;
    const freeShipping = document.getElementById('freeShippingOnly')?.checked ? 1 : 0;
    const total = activeFilters + freeShipping;
    
    const countBadge = document.getElementById('activeFiltersCount');
    if (total > 0) {
        countBadge.textContent = total;
        countBadge.classList.remove('hidden');
    } else {
        countBadge.classList.add('hidden');
    }
}

// Sorting
document.getElementById('sortSelect').addEventListener('change', function() {
    const sortValue = this.value;
    const grid = document.getElementById('productsGrid');
    const products = Array.from(grid.children);
    
    grid.classList.add('loading-state');
    
    setTimeout(() => {
        products.sort((a, b) => {
            switch(sortValue) {
                case 'price-low':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price-high':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                case 'rating':
                    return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
                case 'popular':
                    return parseInt(b.dataset.reviews) - parseInt(a.dataset.reviews);
                case 'newest':
                    return parseInt(b.dataset.id || 0) - parseInt(a.dataset.id || 0);
                default:
                    return 0;
            }
        });
        
        products.forEach(product => grid.appendChild(product));
        grid.classList.remove('loading-state');
    }, 300);
});

// View toggle
document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const grid = document.getElementById('productsGrid');
        if (this.dataset.view === 'list') {
            grid.classList.add('list-view');
        } else {
            grid.classList.remove('list-view');
        }
    });
});

// Filter functionality
document.querySelectorAll('.price-filter, .brand-filter').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        filterProducts();
        updateFilterCount();
    });
});

document.getElementById('freeShippingOnly')?.addEventListener('change', function() {
    filterProducts();
    updateFilterCount();
});

function filterProducts() {
    const products = document.querySelectorAll('.product-card');
    const checkedPrices = Array.from(document.querySelectorAll('.price-filter:checked'));
    const checkedBrands = Array.from(document.querySelectorAll('.brand-filter:checked'));
    const freeShippingOnly = document.getElementById('freeShippingOnly')?.checked;
    
    products.forEach(product => {
        let showProduct = true;
        const price = parseFloat(product.dataset.price);
        const brand = product.dataset.brand;
        const hasShipping = product.dataset.shipping === '1';
        
        if (checkedPrices.length > 0) {
            const matchesPrice = checkedPrices.some(checkbox => {
                const min = parseFloat(checkbox.dataset.min);
                const max = parseFloat(checkbox.dataset.max);
                return price >= min && price <= max;
            });
            if (!matchesPrice) showProduct = false;
        }
        
        if (checkedBrands.length > 0) {
            const matchesBrand = checkedBrands.some(checkbox => {
                return checkbox.dataset.brand === brand;
            });
            if (!matchesBrand) showProduct = false;
        }
        
        if (freeShippingOnly && !hasShipping) {
            showProduct = false;
        }
        
        product.style.display = showProduct ? '' : 'none';
    });
}

// Horizontal scroll
function setupScrollButtons(containerId, leftBtnId, rightBtnId) {
    const container = document.getElementById(containerId);
    const leftBtn = document.getElementById(leftBtnId);
    const rightBtn = document.getElementById(rightBtnId);
    
    if (container && leftBtn && rightBtn) {
        const scrollAmount = window.innerWidth < 640 ? 200 : 300;
        
        leftBtn.addEventListener('click', () => {
            container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        });
        
        rightBtn.addEventListener('click', () => {
            container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        });
    }
}

setupScrollButtons('alsoLikeContainer', 'alsoLikeScrollLeft', 'alsoLikeScrollRight');
setupScrollButtons('bestSellingContainer', 'bestSellingScrollLeft', 'bestSellingScrollRight');
setupScrollButtons('trendingContainer', 'trendingScrollLeft', 'trendingScrollRight');

// Product card click
document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('click', function(e) {
        if (!e.target.closest('.wishlist-btn') && !e.target.closest('button')) {
            const productId = this.dataset.id;
            window.location.href = `product-detail.php?id=${productId}`;
        }
    });
});

// Wishlist
document.querySelectorAll('.wishlist-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        const icon = this.querySelector('i');
        
        if (icon.classList.contains('far')) {
            icon.classList.remove('far');
            icon.classList.add('fas');
            this.classList.add('active');
            showNotification('Added to wishlist!');
        } else {
            icon.classList.remove('fas');
            icon.classList.add('far');
            this.classList.remove('active');
            showNotification('Removed from wishlist');
        }
    });
});

// Add to cart
document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        showNotification('Added to cart!');
    });
});

// Notification
function showNotification(message) {
    const notification = document.createElement('div');
    notification.className = 'notification';
    notification.style.opacity = '0';
    notification.style.transform = 'translateY(-20px)';
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-check-circle notification-icon"></i>
            <span class="notification-text">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '1';
        notification.style.transform = 'translateY(0)';
    }, 10);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateY(-20px)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Initialize
updateFilterCount();

// Smooth scroll on page change
if (window.location.search.includes('page=')) {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<?php require_once '../includes/footer.php'; ?>