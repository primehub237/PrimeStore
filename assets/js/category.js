// Category Page JavaScript

// DOM Elements
const mobileFilterBtn = document.getElementById('mobileFilterBtn');
const filterDrawer = document.getElementById('filterDrawer');
const filterOverlay = document.getElementById('filterOverlay');
const closeDrawer = document.getElementById('closeDrawer');
const applyFilters = document.getElementById('applyFilters');
const clearFilters = document.getElementById('clearFilters');
const filterBadge = document.getElementById('filterBadge');
const sortSelect = document.getElementById('sortSelect');
const productsGrid = document.getElementById('productsGrid');
const productCount = document.getElementById('productCount');
const viewBtns = document.querySelectorAll('.view-btn');
const filterChips = document.querySelectorAll('.filter-chip');
const loadMoreBtn = document.getElementById('loadMoreBtn');
const floatingCart = document.getElementById('floatingCart');

// State
let activeFilters = {
    price: [],
    brand: [],
    shipping: false,
    category: 'all'
};

let cartCount = 0;

// Mobile Filter Drawer
function openFilterDrawer() {
    filterDrawer.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeFilterDrawer() {
    filterDrawer.classList.remove('active');
    document.body.style.overflow = '';
}

mobileFilterBtn?.addEventListener('click', openFilterDrawer);
filterOverlay?.addEventListener('click', closeFilterDrawer);
closeDrawer?.addEventListener('click', closeFilterDrawer);
applyFilters?.addEventListener('click', () => {
    applyProductFilters();
    closeFilterDrawer();
});

// Filter Badge Update
function updateFilterBadge() {
    const totalFilters = activeFilters.price.length + 
                        activeFilters.brand.length + 
                        (activeFilters.shipping ? 1 : 0);
    
    if (totalFilters > 0) {
        filterBadge.textContent = totalFilters;
        filterBadge.classList.add('active');
    } else {
        filterBadge.classList.remove('active');
    }
}

// Filter Checkboxes
document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const filterType = this.dataset.filter;
        
        if (filterType === 'price') {
            const priceRange = {
                min: parseFloat(this.dataset.min),
                max: parseFloat(this.dataset.max)
            };
            
            if (this.checked) {
                activeFilters.price.push(priceRange);
            } else {
                activeFilters.price = activeFilters.price.filter(
                    range => range.min !== priceRange.min || range.max !== priceRange.max
                );
            }
        } else if (filterType === 'brand') {
            const brand = this.dataset.value;
            if (this.checked) {
                activeFilters.brand.push(brand);
            } else {
                activeFilters.brand = activeFilters.brand.filter(b => b !== brand);
            }
        } else if (filterType === 'shipping') {
            activeFilters.shipping = this.checked;
        }
        
        updateFilterBadge();
    });
});

// Apply Filters
function applyProductFilters() {
    const products = document.querySelectorAll('.product-card');
    let visibleCount = 0;
    
    products.forEach(product => {
        const price = parseFloat(product.dataset.price);
        const brand = product.dataset.brand;
        const shipping = product.dataset.shipping === '1';
        const category = product.dataset.category;
        
        let show = true;
        
        // Category filter
        if (activeFilters.category !== 'all' && category !== activeFilters.category) {
            show = false;
        }
        
        // Price filter
        if (activeFilters.price.length > 0) {
            const matchesPrice = activeFilters.price.some(range => 
                price >= range.min && price <= range.max
            );
            if (!matchesPrice) show = false;
        }
        
        // Brand filter
        if (activeFilters.brand.length > 0) {
            if (!activeFilters.brand.includes(brand)) show = false;
        }
        
        // Shipping filter
        if (activeFilters.shipping && !shipping) {
            show = false;
        }
        
        if (show) {
            product.style.display = '';
            visibleCount++;
            // Add fade-in animation
            product.style.animation = 'fadeIn 0.5s ease-out';
        } else {
            product.style.display = 'none';
        }
    });
    
    productCount.textContent = visibleCount;
}

// Clear Filters
clearFilters?.addEventListener('click', () => {
    activeFilters = {
        price: [],
        brand: [],
        shipping: false,
        category: activeFilters.category
    };
    
    document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    applyProductFilters();
    updateFilterBadge();
    
    showToast('Filters cleared', 'info');
});

// Category Filter Chips
filterChips.forEach(chip => {
    chip.addEventListener('click', function() {
        filterChips.forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        
        activeFilters.category = this.dataset.category;
        applyProductFilters();
        
        // Smooth scroll to products
        productsGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
});

// Sort Products
sortSelect?.addEventListener('change', function() {
    const sortValue = this.value;
    const products = Array.from(document.querySelectorAll('.product-card'));
    
    productsGrid.classList.add('loading');
    
    setTimeout(() => {
        products.sort((a, b) => {
            switch(sortValue) {
                case 'price-low':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price-high':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                case 'rating':
                    return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
                case 'newest':
                    return parseInt(b.dataset.id) - parseInt(a.dataset.id);
                default:
                    return 0;
            }
        });
        
        products.forEach(product => productsGrid.appendChild(product));
        productsGrid.classList.remove('loading');
        
        showToast(`Sorted by: ${sortSelect.options[sortSelect.selectedIndex].text}`, 'success');
    }, 300);
});

// View Toggle
viewBtns.forEach(btn => {
    btn.addEventListener('click', function() {
        viewBtns.forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        const view = this.dataset.view;
        if (view === 'list') {
            productsGrid.classList.add('list-view');
        } else {
            productsGrid.classList.remove('list-view');
        }
        
        showToast(`${view.charAt(0).toUpperCase() + view.slice(1)} view activated`, 'info');
    });
});

// Wishlist Toggle
document.querySelectorAll('.wishlist-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        
        this.classList.toggle('active');
        const isActive = this.classList.contains('active');
        
        // Add bounce animation
        this.style.animation = 'none';
        setTimeout(() => {
            this.style.animation = 'bounce 0.5s ease';
        }, 10);
        
        showToast(
            isActive ? '❤️ Added to wishlist' : 'Removed from wishlist',
            isActive ? 'success' : 'info'
        );
    });
});

// Add to Cart
document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        
        cartCount++;
        updateCartCount();
        
        // Add ripple effect
        const ripple = document.createElement('span');
        ripple.classList.add('ripple');
        this.appendChild(ripple);
        
        setTimeout(() => ripple.remove(), 600);
        
        // Animate button
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = '';
        }, 100);
        
        showToast('🛒 Added to cart!', 'success');
    });
});

// Update Cart Count
function updateCartCount() {
    const cartBadge = floatingCart?.querySelector('.cart-count');
    if (cartBadge) {
        cartBadge.textContent = cartCount;
        cartBadge.style.animation = 'bounce 0.5s ease';
    }
}

// Product Card Click
document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('click', function(e) {
        // Don't navigate if clicking on buttons
        if (e.target.closest('button')) return;
        
        const productId = this.dataset.id;
        // window.location.href = `product-detail.php?id=${productId}`;
        showToast('Opening product details...', 'info');
    });
});

// Quick View
document.querySelectorAll('.quick-view-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        showToast('Quick view coming soon!', 'info');
    });
});

// Load More
loadMoreBtn?.addEventListener('click', function() {
    this.disabled = true;
    this.innerHTML = '<span>Loading...</span>';
    
    setTimeout(() => {
        showToast('No more products to load', 'info');
        this.disabled = false;
        this.innerHTML = `
            <span>Load More Products</span>
            <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
        `;
    }, 1500);
});

// Floating Cart
floatingCart?.addEventListener('click', function() {
    showToast('Opening cart...', 'info');
    // window.location.href = 'cart.php';
});

// Toast Notification
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            ${getToastIcon(type)}
            <span>${message}</span>
        </div>
    `;
    
    // Add styles
    Object.assign(toast.style, {
        position: 'fixed',
        bottom: '2rem',
        left: '50%',
        transform: 'translateX(-50%) translateY(100px)',
        backgroundColor: type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6',
        color: 'white',
        padding: '1rem 1.5rem',
        borderRadius: '0.75rem',
        boxShadow: '0 10px 25px rgba(0, 0, 0, 0.2)',
        zIndex: '9999',
        fontSize: '0.875rem',
        fontWeight: '600',
        display: 'flex',
        alignItems: 'center',
        gap: '0.75rem',
        maxWidth: '90%',
        transition: 'transform 0.3s cubic-bezier(0.4, 0, 0.2, 1)',
        border: '2px solid var(--gold)'
    });
    
    document.body.appendChild(toast);
    
    // Animate in
    requestAnimationFrame(() => {
        toast.style.transform = 'translateX(-50%) translateY(0)';
    });
    
    // Remove after delay
    setTimeout(() => {
        toast.style.transform = 'translateX(-50%) translateY(100px)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

function getToastIcon(type) {
    const icons = {
        success: '✓',
        error: '✗',
        info: 'ℹ'
    };
    return `<span style="font-size: 1.25rem;">${icons[type] || icons.info}</span>`;
}

// Smooth Scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Lazy Loading Images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src || img.src;
                img.classList.add('loaded');
                observer.unobserve(img);
            }
        });
    });
    
    document.querySelectorAll('.product-image').forEach(img => {
        imageObserver.observe(img);
    });
}

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes bounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }
    
    @keyframes ripple {
        0% {
            transform: scale(0);
            opacity: 1;
        }
        100% {
            transform: scale(2);
            opacity: 0;
        }
    }
    
    .ripple {
        position: absolute;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.6);
        pointer-events: none;
        animation: ripple 0.6s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .toast-content {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
`;
document.head.appendChild(style);

// Keyboard Navigation
document.addEventListener('keydown', function(e) {
    // Close drawer with Escape
    if (e.key === 'Escape' && filterDrawer?.classList.contains('active')) {
        closeFilterDrawer();
    }
});

// Handle Window Resize
let resizeTimer;
window.addEventListener('resize', function() {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        // Close mobile drawer on desktop resize
        if (window.innerWidth > 768 && filterDrawer?.classList.contains('active')) {
            closeFilterDrawer();
        }
    }, 250);
});

// Scroll Effects
let lastScroll = 0;
const filterBar = document.querySelector('.filter-bar');

window.addEventListener('scroll', function() {
    const currentScroll = window.pageYOffset;
    
    // Hide/show filter bar on scroll
    if (currentScroll > lastScroll && currentScroll > 300) {
        filterBar?.style.transform = 'translateY(-100%)';
    } else {
        filterBar?.style.transform = 'translateY(0)';
    }
    
    lastScroll = currentScroll;
    
    // Show floating cart after scrolling
    if (currentScroll > 400) {
        floatingCart?.classList.add('visible');
    } else {
        floatingCart?.classList.remove('visible');
    }
});

// Add visibility class styles
const scrollStyle = document.createElement('style');
scrollStyle.textContent = `
    .floating-cart-btn {
        opacity: 0;
        transform: scale(0);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .floating-cart-btn.visible {
        opacity: 1;
        transform: scale(1);
    }
    
    .filter-bar {
        transition: transform 0.3s ease;
    }
`;
document.head.appendChild(scrollStyle);

// Product Card Hover Effects (Desktop)
if (window.innerWidth > 768) {
    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.zIndex = '10';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.zIndex = '1';
        });
    });
}

// Touch Feedback for Mobile
if ('ontouchstart' in window) {
    document.querySelectorAll('button, .filter-chip, .product-card').forEach(element => {
        element.addEventListener('touchstart', function() {
            this.style.opacity = '0.7';
        });
        
        element.addEventListener('touchend', function() {
            this.style.opacity = '1';
        });
    });
}

// Performance: Debounce Filter Application
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

const debouncedFilter = debounce(applyProductFilters, 300);

// Update filter listeners to use debounced version
document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
    const originalListener = checkbox.onclick;
    checkbox.addEventListener('change', debouncedFilter);
});

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial product count
    productCount.textContent = document.querySelectorAll('.product-card').length;
    
    // Add entrance animations with stagger
    const products = document.querySelectorAll('.product-card');
    products.forEach((product, index) => {
        product.style.animationDelay = `${index * 0.05}s`;
    });
    
    // Check if there are URL parameters for filters
    const urlParams = new URLSearchParams(window.location.search);
    const category = urlParams.get('category');
    
    if (category) {
        activeFilters.category = category;
        applyProductFilters();
        
        // Update active chip
        filterChips.forEach(chip => {
            if (chip.dataset.category === category) {
                chip.classList.add('active');
            } else {
                chip.classList.remove('active');
            }
        });
    }
    
    // Welcome message
    setTimeout(() => {
        showToast('Welcome! Browse our premium collection', 'info');
    }, 500);
});

// Search Functionality (if search input exists)
const searchInput = document.querySelector('.search-input');
if (searchInput) {
    searchInput.addEventListener('input', debounce(function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const products = document.querySelectorAll('.product-card');
        let visibleCount = 0;
        
        products.forEach(product => {
            const name = product.querySelector('.product-name').textContent.toLowerCase();
            const brand = product.dataset.brand.toLowerCase();
            
            if (name.includes(searchTerm) || brand.includes(searchTerm)) {
                product.style.display = '';
                visibleCount++;
            } else {
                product.style.display = 'none';
            }
        });
        
        productCount.textContent = visibleCount;
    }, 300));
}

// Price Range Slider (if implemented)
const priceSlider = document.querySelector('.price-slider');
if (priceSlider) {
    priceSlider.addEventListener('input', function() {
        const maxPrice = parseInt(this.value);
        const products = document.querySelectorAll('.product-card');
        let visibleCount = 0;
        
        products.forEach(product => {
            const price = parseFloat(product.dataset.price);
            if (price <= maxPrice) {
                product.style.display = '';
                visibleCount++;
            } else {
                product.style.display = 'none';
            }
        });
        
        productCount.textContent = visibleCount;
    });
}

// Share Product (if share buttons exist)
document.querySelectorAll('.share-btn').forEach(btn => {
    btn.addEventListener('click', async function(e) {
        e.stopPropagation();
        
        const productCard = this.closest('.product-card');
        const productName = productCard.querySelector('.product-name').textContent;
        const productId = productCard.dataset.id;
        
        if (navigator.share) {
            try {
                await navigator.share({
                    title: productName,
                    text: 'Check out this product!',
                    url: `${window.location.origin}/product-detail.php?id=${productId}`
                });
                showToast('Shared successfully!', 'success');
            } catch (err) {
                if (err.name !== 'AbortError') {
                    showToast('Could not share', 'error');
                }
            }
        } else {
            // Fallback: Copy link to clipboard
            const url = `${window.location.origin}/product-detail.php?id=${productId}`;
            navigator.clipboard.writeText(url).then(() => {
                showToast('Link copied to clipboard!', 'success');
            });
        }
    });
});

// Compare Products (if compare feature exists)
let compareList = [];
const maxCompare = 4;

document.querySelectorAll('.compare-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();
        
        const productId = this.closest('.product-card').dataset.id;
        
        if (compareList.includes(productId)) {
            compareList = compareList.filter(id => id !== productId);
            this.classList.remove('active');
            showToast('Removed from compare', 'info');
        } else {
            if (compareList.length >= maxCompare) {
                showToast(`Maximum ${maxCompare} products can be compared`, 'error');
                return;
            }
            compareList.push(productId);
            this.classList.add('active');
            showToast('Added to compare', 'success');
        }
        
        updateCompareButton();
    });
});

function updateCompareButton() {
    const compareBtn = document.querySelector('.compare-floating-btn');
    if (compareBtn) {
        const count = compareBtn.querySelector('.compare-count');
        count.textContent = compareList.length;
        
        if (compareList.length > 0) {
            compareBtn.style.display = 'flex';
        } else {
            compareBtn.style.display = 'none';
        }
    }
}

// Recently Viewed Products
function addToRecentlyViewed(productId) {
    let recentlyViewed = JSON.parse(localStorage.getItem('recentlyViewed') || '[]');
    
    // Remove if already exists
    recentlyViewed = recentlyViewed.filter(id => id !== productId);
    
    // Add to beginning
    recentlyViewed.unshift(productId);
    
    // Keep only last 10
    recentlyViewed = recentlyViewed.slice(0, 10);
    
    localStorage.setItem('recentlyViewed', JSON.stringify(recentlyViewed));
}

// Track product views
document.querySelectorAll('.product-card').forEach(card => {
    card.addEventListener('click', function() {
        const productId = this.dataset.id;
        addToRecentlyViewed(productId);
    });
});

// Analytics tracking (placeholder)
function trackEvent(eventName, eventData) {
    console.log('Event:', eventName, eventData);
    // Integrate with Google Analytics, Facebook Pixel, etc.
    // gtag('event', eventName, eventData);
}

// Track filter changes
document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        trackEvent('filter_applied', {
            filter_type: this.dataset.filter,
            filter_value: this.dataset.value || `${this.dataset.min}-${this.dataset.max}`
        });
    });
});

// Track sort changes
sortSelect?.addEventListener('change', function() {
    trackEvent('sort_changed', {
        sort_type: this.value
    });
});

// Track add to cart
document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const productCard = this.closest('.product-card');
        trackEvent('add_to_cart', {
            product_id: productCard.dataset.id,
            product_price: productCard.dataset.price
        });
    });
});

// Initialize
console.log('Category page initialized');
console.log('Active filters:', activeFilters);
console.log('Total products:', document.querySelectorAll('.product-card').length);