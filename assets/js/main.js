// assets/js/main.js
// Main application logic for PrimeStore

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
    initializeCarousel();
});

// Initialize all functionality
function initializeApp() {
    setupFilterButtons();
    setupAddToCart();
    setupWishlist();
    setupProductCards();
    setupSortButton();
    lazyLoadImages();
}

// Setup filter buttons
function setupFilterButtons() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const productCards = document.querySelectorAll('.product-card');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active state
            filterButtons.forEach(btn => btn.classList.remove('active', 'gold-bg', 'text-white'));
            this.classList.add('active', 'gold-bg', 'text-white');
            
            // Get filter category
            const filterCategory = this.getAttribute('data-filter');
            
            // Filter products
            filterProducts(filterCategory, productCards);
        });
    });
}

// Filter products by category
function filterProducts(category, cards) {
    let visibleCount = 0;
    
    cards.forEach(card => {
        const productCategory = card.getAttribute('data-category');
        
        if (category === 'all' || productCategory === category) {
            card.style.display = 'block';
            // Animate in
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, visibleCount * 50);
            
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show no results message if needed
    if (visibleCount === 0) {
        showNoResults();
    } else {
        hideNoResults();
    }
}

// Setup Add to Cart buttons
function setupAddToCart() {
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.add-to-cart-btn');
        if (button) {
            e.preventDefault();
            addToCart(button);
        }
    });
}

// Add item to cart
function addToCart(button) {
    const productId = button.getAttribute('data-product-id');
    const productName = button.getAttribute('data-product-name');
    const productPrice = button.getAttribute('data-product-price');
    
    // Save original content
    const originalHTML = button.innerHTML;
    
    // Update button to show success
    button.innerHTML = '<i class="fas fa-check"></i>';
    button.classList.add('bg-green-500');
    button.classList.remove('gold-bg');
    
    // Show notification
    showNotification(`${productName} added to cart!`, 'success');
    
    // Update cart count in navbar (if exists)
    updateCartCount();
    
    // Store in localStorage (or send to server)
    saveToCart(productId, productName, productPrice);
    
    // Reset button after 2 seconds
    setTimeout(() => {
        button.innerHTML = originalHTML;
        button.classList.remove('bg-green-500');
        button.classList.add('gold-bg');
    }, 2000);
}

// Setup Wishlist buttons
function setupWishlist() {
    document.addEventListener('click', function(e) {
        const button = e.target.closest('.wishlist-btn');
        if (button) {
            e.preventDefault();
            e.stopPropagation();
            toggleWishlist(button);
        }
    });
}

// Toggle wishlist
function toggleWishlist(button) {
    const productId = button.getAttribute('data-product-id');
    const icon = button.querySelector('i');
    
    if (icon.classList.contains('fas')) {
        // Remove from wishlist
        icon.classList.remove('fas', 'fa-heart');
        icon.classList.add('far', 'fa-heart');
        button.classList.remove('text-red-500');
        showNotification('Removed from wishlist', 'info');
        removeFromWishlist(productId);
    } else {
        // Add to wishlist
        icon.classList.remove('far', 'fa-heart');
        icon.classList.add('fas', 'fa-heart');
        button.classList.add('text-red-500');
        showNotification('Added to wishlist!', 'success');
        saveToWishlist(productId);
    }
}

// Setup product card clicks
function setupProductCards() {
    document.addEventListener('click', function(e) {
        const card = e.target.closest('.product-card');
        if (card && !e.target.closest('button')) {
            const productId = card.getAttribute('data-id');
            viewProduct(productId);
        }
    });
}

// View product details
function viewProduct(productId) {
    // Redirect to product page
    window.location.href = `product.php?id=${productId}`;
}

// Setup sort button
function setupSortButton() {
    const sortBtn = document.getElementById('sortBtn');
    if (sortBtn) {
        sortBtn.addEventListener('click', function() {
            showSortMenu();
        });
    }
}

// Show sort menu
function showSortMenu() {
    const sortOptions = [
        { label: 'Default', value: 'default' },
        { label: 'Price: Low to High', value: 'price_low' },
        { label: 'Price: High to Low', value: 'price_high' },
        { label: 'Name: A to Z', value: 'name' }
    ];
    
    // Create sort menu
    const menu = document.createElement('div');
    menu.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center';
    menu.innerHTML = `
        <div class="bg-white rounded-xl p-6 max-w-sm w-full mx-4">
            <h3 class="text-xl font-bold mb-4">Sort by</h3>
            <div class="space-y-2">
                ${sortOptions.map(opt => `
                    <button class="sort-option w-full text-left px-4 py-3 rounded-lg hover:bg-gray-100 transition-colors" data-sort="${opt.value}">
                        ${opt.label}
                    </button>
                `).join('')}
            </div>
            <button class="mt-4 w-full px-4 py-2 border-2 gold-border rounded-lg gold-text font-semibold hover:gold-bg hover:text-white transition-colors" id="closeSortMenu">
                Cancel
            </button>
        </div>
    `;
    
    document.body.appendChild(menu);
    
    // Handle sort option clicks
    menu.querySelectorAll('.sort-option').forEach(btn => {
        btn.addEventListener('click', function() {
            const sortValue = this.getAttribute('data-sort');
            sortProducts(sortValue);
            menu.remove();
        });
    });
    
    // Handle cancel
    menu.querySelector('#closeSortMenu').addEventListener('click', () => menu.remove());
    menu.addEventListener('click', (e) => {
        if (e.target === menu) menu.remove();
    });
}

// Sort products
function sortProducts(sortBy) {
    const grid = document.getElementById('productsGrid');
    const cards = Array.from(grid.querySelectorAll('.product-card'));
    
    cards.sort((a, b) => {
        const priceA = parseFloat(a.querySelector('.gold-text').textContent.replace(', '));
        const priceB = parseFloat(b.querySelector('.gold-text').textContent.replace(', '));
        const nameA = a.querySelector('h3').textContent;
        const nameB = b.querySelector('h3').textContent;
        
        switch(sortBy) {
            case 'price_low':
                return priceA - priceB;
            case 'price_high':
                return priceB - priceA;
            case 'name':
                return nameA.localeCompare(nameB);
            default:
                return 0;
        }
    });
    
    // Re-append sorted cards
    cards.forEach(card => grid.appendChild(card));
    
    showNotification('Products sorted!', 'success');
}

// Show notification toast
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = 'fixed top-24 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full max-w-sm';
    
    // Set colors based on type
    const colors = {
        success: 'bg-green-500 text-white',
        error: 'bg-red-500 text-white',
        info: 'bg-gray-800 text-white',
        warning: 'bg-yellow-500 text-white'
    };
    
    notification.className += ` ${colors[type] || colors.info}`;
    
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        info: 'info-circle',
        warning: 'exclamation-triangle'
    };
    
    notification.innerHTML = `
        <div class="flex items-center space-x-3">
            <i class="fas fa-${icons[type] || icons.info} text-xl"></i>
            <span class="font-medium">${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Slide in
    setTimeout(() => notification.classList.remove('translate-x-full'), 100);
    
    // Auto remove
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Show no results message
function showNoResults() {
    const grid = document.getElementById('productsGrid');
    let noResults = document.getElementById('noResults');
    
    if (!noResults) {
        noResults = document.createElement('div');
        noResults.id = 'noResults';
        noResults.className = 'col-span-full text-center py-12';
        noResults.innerHTML = `
            <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">No products found in this category</p>
        `;
        grid.appendChild(noResults);
    }
}

// Hide no results message
function hideNoResults() {
    const noResults = document.getElementById('noResults');
    if (noResults) {
        noResults.remove();
    }
}

// Save to cart (localStorage or API)
function saveToCart(productId, productName, productPrice) {
    let cart = JSON.parse(localStorage.getItem('cart') || '[]');
    
    // Check if product already exists
    const existingItem = cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: productId,
            name: productName,
            price: parseFloat(productPrice),
            quantity: 1
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
}

// Save to wishlist
function saveToWishlist(productId) {
    let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    if (!wishlist.includes(productId)) {
        wishlist.push(productId);
        localStorage.setItem('wishlist', JSON.stringify(wishlist));
    }
}

// Remove from wishlist
function removeFromWishlist(productId) {
    let wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    wishlist = wishlist.filter(id => id !== productId);
    localStorage.setItem('wishlist', JSON.stringify(wishlist));
}

// Update cart count in navbar
function updateCartCount() {
    const cart = JSON.parse(localStorage.getItem('cart') || '[]');
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    
    const cartBadge = document.querySelector('.fa-shopping-cart').parentElement.querySelector('span');
    if (cartBadge) {
        cartBadge.textContent = totalItems;
    }
}

// Lazy load images
function lazyLoadImages() {
    const images = document.querySelectorAll('img[loading="lazy"]');
    
    if ('IntersectionObserver' in window) {
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.classList.add('loaded');
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    } else {
        // Fallback for browsers that don't support IntersectionObserver
        images.forEach(img => img.classList.add('loaded'));
    }
}

// Initialize cart count on page load
updateCartCount();

// Export functions for global use
window.PrimeStore = {
    addToCart,
    toggleWishlist,
    viewProduct,
    showNotification,
    filterProducts,
    sortProducts,
    updateCartCount
};