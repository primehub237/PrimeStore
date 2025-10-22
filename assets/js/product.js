// assets/js/products.js
// Enhanced product listing interactivity

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize
    initializeFilters();
    initializeSorting();
    initializeViewToggle();
    initializeProductCards();
    initializeQuickView();
    initializeCompare();
    initializeWishlist();
    animateOnScroll();

    // Filter System
    function initializeFilters() {
        const filterBtns = document.querySelectorAll('.filter-btn');
        
        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const filterType = this.id.replace('Filter', '');
                showFilterModal(filterType);
            });
        });

        // Price filter
        const priceFilter = document.getElementById('priceFilter');
        if (priceFilter) {
            priceFilter.addEventListener('click', function(e) {
                e.stopPropagation();
                this.classList.toggle('active');
                applyPriceFilter();
            });
        }

        // Shipping filter
        const shippingFilter = document.getElementById('shippingFilter');
        if (shippingFilter) {
            shippingFilter.addEventListener('click', function(e) {
                e.stopPropagation();
                this.classList.toggle('active');
                filterByShipping();
            });
        }
    }

    function showFilterModal(filterType) {
        // Create modal for advanced filtering
        console.log(`Opening ${filterType} filter modal`);
        // You can implement a modal here
    }

    function applyPriceFilter() {
        const products = document.querySelectorAll('.product-card');
        const minPrice = 0;
        const maxPrice = 500;

        products.forEach(product => {
            const price = parseFloat(product.dataset.price);
            if (price >= minPrice && price <= maxPrice) {
                product.style.display = '';
            } else {
                product.style.display = 'none';
            }
        });
    }

    function filterByShipping() {
        const products = document.querySelectorAll('.product-card');
        const shippingFilter = document.getElementById('shippingFilter');
        const isActive = shippingFilter.classList.contains('active');

        products.forEach(product => {
            const hasFreeShipping = product.querySelector('.product-shipping');
            if (isActive) {
                product.style.display = hasFreeShipping ? '' : 'none';
            } else {
                product.style.display = '';
            }
        });
    }

    // Sorting System
    function initializeSorting() {
        const sortSelect = document.getElementById('sortSelect');
        if (!sortSelect) return;

        sortSelect.addEventListener('change', function() {
            const sortValue = this.value;
            sortProducts(sortValue);
        });
    }

    function sortProducts(sortType) {
        const grid = document.getElementById('productsGrid');
        if (!grid) return;

        const products = Array.from(grid.children);
        
        products.sort((a, b) => {
            switch(sortType) {
                case 'price-low':
                    return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                case 'price-high':
                    return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                case 'rating':
                    return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
                case 'newest':
                    // Assuming newer products have higher IDs
                    return parseInt(b.dataset.id || 0) - parseInt(a.dataset.id || 0);
                default:
                    return 0;
            }
        });
        
        // Animate sorting
        grid.style.opacity = '0.5';
        setTimeout(() => {
            products.forEach(product => grid.appendChild(product));
            grid.style.opacity = '1';
        }, 200);
    }

    // View Toggle
    function initializeViewToggle() {
        const viewBtns = document.querySelectorAll('.view-btn');
        const grid = document.getElementById('productsGrid');
        
        viewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                viewBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const view = this.dataset.view;
                if (view === 'list') {
                    grid.style.gridTemplateColumns = '1fr';
                    grid.querySelectorAll('.product-card').forEach(card => {
                        card.style.display = 'flex';
                        card.style.flexDirection = 'row';
                        card.querySelector('.product-image').style.width = '200px';
                        card.querySelector('.product-image').style.height = '200px';
                    });
                } else {
                    grid.style.gridTemplateColumns = 'repeat(auto-fill, minmax(220px, 1fr))';
                    grid.querySelectorAll('.product-card').forEach(card => {
                        card.style.display = 'block';
                        card.style.flexDirection = '';
                        card.querySelector('.product-image').style.width = '100%';
                        card.querySelector('.product-image').style.height = '220px';
                    });
                }
            });
        });
    }

    // Product Card Interactions
    function initializeProductCards() {
        const productCards = document.querySelectorAll('.product-card');
        
        productCards.forEach(card => {
            // Add hover effect
            card.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s ease';
            });

            // Click to view product
            card.addEventListener('click', function(e) {
                // Don't trigger if clicking on action buttons
                if (e.target.closest('.product-actions')) return;
                
                const productId = this.dataset.productId || Math.floor(Math.random() * 1000);
                viewProductDetail(productId);
            });

            // Add quick action buttons
            addQuickActions(card);
        });
    }

    function addQuickActions(card) {
        const info = card.querySelector('.product-info');
        if (!info || info.querySelector('.product-actions')) return;

        const actions = document.createElement('div');
        actions.className = 'product-actions';
        actions.innerHTML = `
            <button class="action-btn wishlist-btn" title="Add to Wishlist">
                <span class="heart-icon">♡</span>
            </button>
            <button class="action-btn compare-btn" title="Add to Compare">
                <span>⚖</span>
            </button>
            <button class="action-btn quick-view-btn" title="Quick View">
                <span>👁</span>
            </button>
        `;
        
        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .product-actions {
                display: flex;
                gap: 5px;
                margin-top: 10px;
                opacity: 0;
                transition: opacity 0.3s ease;
            }
            .product-card:hover .product-actions {
                opacity: 1;
            }
            .action-btn {
                flex: 1;
                padding: 8px;
                border: 1px solid #d5d9d9;
                background: white;
                border-radius: 4px;
                cursor: pointer;
                transition: all 0.2s;
                font-size: 16px;
            }
            .action-btn:hover {
                background: #f7f7f7;
                border-color: #0066c0;
                transform: translateY(-2px);
            }
            .action-btn.active {
                background: #febd69;
                border-color: #febd69;
            }
            .wishlist-btn.active .heart-icon {
                content: '♥';
            }
        `;
        document.head.appendChild(style);
        
        info.appendChild(actions);

        // Add event listeners
        actions.querySelector('.wishlist-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            toggleWishlist(e.currentTarget);
        });

        actions.querySelector('.compare-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            toggleCompare(e.currentTarget);
        });

        actions.querySelector('.quick-view-btn').addEventListener('click', (e) => {
            e.stopPropagation();
            showQuickView(card);
        });
    }

    function viewProductDetail(productId) {
        // Navigate to product detail page
        window.location.href = `product-detail.php?id=${productId}`;
    }

    // Quick View Modal
    function initializeQuickView() {
        // Quick view will be initialized when needed
    }

    function showQuickView(card) {
        const modal = createQuickViewModal(card);
        document.body.appendChild(modal);
        
        setTimeout(() => modal.classList.add('show'), 10);

        // Close modal
        modal.querySelector('.close-modal').addEventListener('click', () => {
            modal.classList.remove('show');
            setTimeout(() => modal.remove(), 300);
        });

        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('show');
                setTimeout(() => modal.remove(), 300);
            }
        });
    }

    function createQuickViewModal(card) {
        const modal = document.createElement('div');
        modal.className = 'quick-view-modal';
        
        const productName = card.querySelector('.product-name').textContent;
        const productPrice = card.querySelector('.product-price').textContent;
        const productImage = card.querySelector('.product-image').src;
        const productBrand = card.querySelector('.product-brand').textContent;
        
        modal.innerHTML = `
            <div class="modal-content">
                <button class="close-modal">&times;</button>
                <div class="modal-grid">
                    <div class="modal-image">
                        <img src="${productImage}" alt="${productName}">
                    </div>
                    <div class="modal-details">
                        <div class="modal-brand">${productBrand}</div>
                        <h2>${productName}</h2>
                        <div class="modal-price">${productPrice}</div>
                        <div class="modal-rating">
                            ${card.querySelector('.product-rating').innerHTML}
                        </div>
                        <div class="modal-description">
                            <p>High-quality component with excellent performance and reliability. Perfect for gaming, productivity, and professional use.</p>
                        </div>
                        <div class="modal-actions">
                            <button class="add-to-cart-btn">Add to Cart</button>
                            <button class="buy-now-btn">Buy Now</button>
                        </div>
                        <div class="modal-features">
                            <h3>Key Features:</h3>
                            <ul>
                                <li>✓ Premium quality components</li>
                                <li>✓ Extended warranty included</li>
                                <li>✓ Free shipping available</li>
                                <li>✓ Easy returns within 30 days</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Add modal styles
        const style = document.createElement('style');
        style.textContent = `
            .quick-view-modal {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.7);
                z-index: 10000;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.3s ease;
                padding: 20px;
            }
            .quick-view-modal.show {
                opacity: 1;
            }
            .modal-content {
                background: white;
                border-radius: 12px;
                max-width: 900px;
                width: 100%;
                max-height: 90vh;
                overflow-y: auto;
                position: relative;
                padding: 30px;
                transform: scale(0.9);
                transition: transform 0.3s ease;
            }
            .quick-view-modal.show .modal-content {
                transform: scale(1);
            }
            .close-modal {
                position: absolute;
                top: 15px;
                right: 15px;
                background: none;
                border: none;
                font-size: 32px;
                cursor: pointer;
                color: #888;
                width: 40px;
                height: 40px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                transition: all 0.2s;
            }
            .close-modal:hover {
                background: #f0f0f0;
                color: #111;
            }
            .modal-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 30px;
            }
            .modal-image img {
                width: 100%;
                height: auto;
                object-fit: contain;
            }
            .modal-brand {
                color: #007185;
                font-size: 14px;
                font-weight: 600;
                margin-bottom: 10px;
                text-transform: uppercase;
            }
            .modal-details h2 {
                font-size: 24px;
                margin-bottom: 15px;
                font-weight: 400;
            }
            .modal-price {
                font-size: 28px;
                color: #b12704;
                font-weight: 600;
                margin-bottom: 15px;
            }
            .modal-rating {
                margin-bottom: 20px;
            }
            .modal-description {
                margin-bottom: 20px;
                color: #565959;
                line-height: 1.6;
            }
            .modal-actions {
                display: flex;
                gap: 10px;
                margin-bottom: 25px;
            }
            .add-to-cart-btn, .buy-now-btn {
                flex: 1;
                padding: 12px 24px;
                border: none;
                border-radius: 8px;
                font-size: 16px;
                cursor: pointer;
                transition: all 0.2s;
                font-weight: 600;
            }
            .add-to-cart-btn {
                background: #ffd814;
                color: #0f1111;
            }
            .add-to-cart-btn:hover {
                background: #f7ca00;
            }
            .buy-now-btn {
                background: #ffa41c;
                color: #0f1111;
            }
            .buy-now-btn:hover {
                background: #fa8900;
            }
            .modal-features h3 {
                font-size: 18px;
                margin-bottom: 12px;
                font-weight: 500;
            }
            .modal-features ul {
                list-style: none;
                padding: 0;
            }
            .modal-features li {
                padding: 8px 0;
                color: #565959;
            }
            @media (max-width: 768px) {
                .modal-grid {
                    grid-template-columns: 1fr;
                }
                .modal-content {
                    padding: 20px;
                }
            }
        `;
        document.head.appendChild(style);

        return modal;
    }

    // Compare System
    function initializeCompare() {
        window.compareList = [];
    }

    function toggleCompare(btn) {
        btn.classList.toggle('active');
        const card = btn.closest('.product-card');
        const productName = card.querySelector('.product-name').textContent;
        
        if (btn.classList.contains('active')) {
            window.compareList.push(productName);
            showNotification(`Added to compare (${window.compareList.length})`);
        } else {
            window.compareList = window.compareList.filter(p => p !== productName);
            showNotification('Removed from compare');
        }

        if (window.compareList.length > 0) {
            showCompareBar();
        }
    }

    function showCompareBar() {
        let compareBar = document.querySelector('.compare-bar');
        if (!compareBar) {
            compareBar = document.createElement('div');
            compareBar.className = 'compare-bar';
            compareBar.innerHTML = `
                <div class="compare-content">
                    <span class="compare-count">Compare (<span id="compareCount">0</span>)</span>
                    <button class="view-compare-btn">View Comparison</button>
                    <button class="clear-compare-btn">Clear All</button>
                </div>
            `;
            document.body.appendChild(compareBar);

            // Add styles
            const style = document.createElement('style');
            style.textContent = `
                .compare-bar {
                    position: fixed;
                    bottom: 20px;
                    right: 20px;
                    background: #232f3e;
                    color: white;
                    padding: 15px 25px;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                    z-index: 9999;
                    animation: slideIn 0.3s ease;
                }
                @keyframes slideIn {
                    from { transform: translateY(100px); opacity: 0; }
                    to { transform: translateY(0); opacity: 1; }
                }
                .compare-content {
                    display: flex;
                    gap: 15px;
                    align-items: center;
                }
                .compare-count {
                    font-weight: 600;
                }
                .view-compare-btn, .clear-compare-btn {
                    padding: 8px 16px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    font-size: 14px;
                    transition: all 0.2s;
                }
                .view-compare-btn {
                    background: #febd69;
                    color: #111;
                    font-weight: 600;
                }
                .view-compare-btn:hover {
                    background: #f7ca00;
                }
                .clear-compare-btn {
                    background: transparent;
                    color: white;
                    border: 1px solid white;
                }
                .clear-compare-btn:hover {
                    background: rgba(255,255,255,0.1);
                }
            `;
            document.head.appendChild(style);

            // Event listeners
            compareBar.querySelector('.view-compare-btn').addEventListener('click', () => {
                alert('Opening comparison page...\nComparing: ' + window.compareList.join(', '));
            });

            compareBar.querySelector('.clear-compare-btn').addEventListener('click', () => {
                window.compareList = [];
                document.querySelectorAll('.compare-btn.active').forEach(btn => {
                    btn.classList.remove('active');
                });
                compareBar.remove();
                showNotification('Comparison list cleared');
            });
        }

        document.getElementById('compareCount').textContent = window.compareList.length;
    }

    // Wishlist System
    function initializeWishlist() {
        window.wishlist = JSON.parse(localStorage.getItem('wishlist') || '[]');
    }

    function toggleWishlist(btn) {
        btn.classList.toggle('active');
        const card = btn.closest('.product-card');
        const productName = card.querySelector('.product-name').textContent;
        const heartIcon = btn.querySelector('.heart-icon');
        
        if (btn.classList.contains('active')) {
            heartIcon.textContent = '♥';
            window.wishlist.push(productName);
            showNotification('Added to wishlist ♥');
        } else {
            heartIcon.textContent = '♡';
            window.wishlist = window.wishlist.filter(p => p !== productName);
            showNotification('Removed from wishlist');
        }

        localStorage.setItem('wishlist', JSON.stringify(window.wishlist));
    }

    // Notification System
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.className = 'notification';
        notification.textContent = message;
        
        const style = document.createElement('style');
        style.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                background: #232f3e;
                color: white;
                padding: 15px 25px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                z-index: 10001;
                animation: slideInTop 0.3s ease, fadeOut 0.3s ease 2.7s;
            }
            @keyframes slideInTop {
                from { transform: translateY(-100px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            @keyframes fadeOut {
                to { opacity: 0; transform: translateY(-20px); }
            }
        `;
        if (!document.querySelector('style[data-notification]')) {
            style.setAttribute('data-notification', 'true');
            document.head.appendChild(style);
        }

        document.body.appendChild(notification);
        setTimeout(() => notification.remove(), 3000);
    }

    // Scroll Animations
    function animateOnScroll() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.animation = 'fadeInUp 0.5s ease forwards';
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.product-card').forEach(card => {
            observer.observe(card);
        });

        const style = document.createElement('style');
        style.textContent = `
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        `;
        document.head.appendChild(style);
    }

    // Search functionality
    const searchInput = document.querySelector('.search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const products = document.querySelectorAll('.product-card');
            
            products.forEach(product => {
                const productName = product.querySelector('.product-name').textContent.toLowerCase();
                const productBrand = product.querySelector('.product-brand').textContent.toLowerCase();
                
                if (productName.includes(searchTerm) || productBrand.includes(searchTerm)) {
                    product.style.display = '';
                } else {
                    product.style.display = 'none';
                }
            });
        });
    }

    console.log('Product listing initialized successfully! 🚀');
});

// Export functions for use in other scripts
window.ProductSystem = {
    sortProducts: (sortType) => sortProducts(sortType),
    filterByPrice: (min, max) => applyPriceFilter(min, max),
    showNotification: (message) => showNotification(message)
};