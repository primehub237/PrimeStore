// assets/js/product-detail.js

document.addEventListener('DOMContentLoaded', function() {
    
    // ============ Image Gallery Functionality ============
    const thumbnails = document.querySelectorAll('.thumbnail-item');
    const mainImage = document.getElementById('mainImage');
    
    thumbnails.forEach(thumbnail => {
        thumbnail.addEventListener('click', function() {
            // Remove active class from all thumbnails
            thumbnails.forEach(t => t.classList.remove('active'));
            
            // Add active class to clicked thumbnail
            this.classList.add('active');
            
            // Change main image with smooth transition
            const newImageSrc = this.getAttribute('data-image');
            mainImage.style.opacity = '0';
            
            setTimeout(() => {
                mainImage.src = newImageSrc;
                mainImage.style.opacity = '1';
            }, 200);
        });
    });
    
    // ============ Image Zoom Modal ============
    const zoomBtn = document.getElementById('zoomBtn');
    const zoomModal = document.getElementById('imageZoomModal');
    const zoomModalClose = document.querySelector('.zoom-modal-close');
    const zoomModalImage = document.getElementById('zoomModalImage');
    
    if (zoomBtn) {
        zoomBtn.addEventListener('click', function() {
            zoomModalImage.src = mainImage.src;
            zoomModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }
    
    if (zoomModalClose) {
        zoomModalClose.addEventListener('click', closeZoomModal);
    }
    
    if (zoomModal) {
        zoomModal.addEventListener('click', function(e) {
            if (e.target === zoomModal) {
                closeZoomModal();
            }
        });
    }
    
    function closeZoomModal() {
        zoomModal.classList.remove('active');
        document.body.style.overflow = 'auto';
    }
    
    // ESC key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && zoomModal.classList.contains('active')) {
            closeZoomModal();
        }
    });
    
    // ============ Wishlist Functionality ============
    const wishlistBtns = document.querySelectorAll('.wishlist-btn, .wishlist-btn-large');
    
    wishlistBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            this.classList.toggle('active');
            const icon = this.querySelector('i');
            
            if (this.classList.contains('active')) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                
                // Show notification
                showNotification('Added to wishlist!', 'success');
            } else {
                icon.classList.remove('fas');
                icon.classList.add('far');
                
                showNotification('Removed from wishlist', 'info');
            }
        });
    });
    
    // ============ Quantity Controls ============
    const quantityInput = document.getElementById('quantityInput');
    const decreaseBtn = document.getElementById('decreaseQty');
    const increaseBtn = document.getElementById('increaseQty');
    const maxStock = parseInt(quantityInput.getAttribute('max'));
    
    if (decreaseBtn) {
        decreaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue > 1) {
                quantityInput.value = currentValue - 1;
                animateQuantityChange();
            }
        });
    }
    
    if (increaseBtn) {
        increaseBtn.addEventListener('click', function() {
            let currentValue = parseInt(quantityInput.value);
            if (currentValue < maxStock) {
                quantityInput.value = currentValue + 1;
                animateQuantityChange();
            } else {
                showNotification('Maximum stock reached!', 'warning');
            }
        });
    }
    
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            if (isNaN(value) || value < 1) {
                this.value = 1;
            } else if (value > maxStock) {
                this.value = maxStock;
                showNotification('Maximum stock reached!', 'warning');
            }
        });
    }
    
    function animateQuantityChange() {
        quantityInput.style.transform = 'scale(1.2)';
        quantityInput.style.color = '#D4AF37';
        setTimeout(() => {
            quantityInput.style.transform = 'scale(1)';
            quantityInput.style.color = '';
        }, 200);
    }
    
    // ============ Add to Cart Functionality ============
    const addToCartBtn = document.getElementById('addToCartBtn');
    
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', function() {
            const quantity = parseInt(quantityInput.value);
            const productName = document.querySelector('.product-title').textContent;
            
            // Animate button
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
            
            // Show success notification
            showNotification(`${quantity} item(s) added to cart!`, 'success');
            
            // Here you would typically send data to server/cart
            console.log('Added to cart:', {
                product: productName,
                quantity: quantity
            });
        });
    }
    
    // ============ Buy Now Functionality ============
    const buyNowBtn = document.getElementById('buyNowBtn');
    
    if (buyNowBtn) {
        buyNowBtn.addEventListener('click', function() {
            const quantity = parseInt(quantityInput.value);
            const productName = document.querySelector('.product-title').textContent;
            
            // Animate button
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
            
            showNotification('Redirecting to checkout...', 'info');
            
            // Redirect to checkout after short delay
            setTimeout(() => {
                // window.location.href = 'checkout.php?product=' + productId + '&qty=' + quantity;
                console.log('Buy Now:', {
                    product: productName,
                    quantity: quantity
                });
            }, 1000);
        });
    }
    
    // ============ Tabs Functionality ============
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabPanels = document.querySelectorAll('.tab-panel');
    
    tabBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panels
            tabBtns.forEach(b => b.classList.remove('active'));
            tabPanels.forEach(p => p.classList.remove('active'));
            
            // Add active class to clicked button and corresponding panel
            this.classList.add('active');
            document.getElementById(targetTab).classList.add('active');
            
            // Smooth scroll to tabs section on mobile
            if (window.innerWidth < 768) {
                document.querySelector('.product-tabs-section').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // ============ Share Functionality ============
    const shareBtns = document.querySelectorAll('.share-btn');
    
    shareBtns.forEach((btn, index) => {
        btn.addEventListener('click', function() {
            const productUrl = window.location.href;
            const productTitle = document.querySelector('.product-title').textContent;
            
            // Animate button
            this.style.transform = 'rotate(360deg) scale(1.2)';
            setTimeout(() => {
                this.style.transform = 'rotate(0deg) scale(1)';
            }, 300);
            
            switch(index) {
                case 0: // Facebook
                    window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(productUrl)}`, '_blank', 'width=600,height=400');
                    showNotification('Opening Facebook share...', 'info');
                    break;
                case 1: // Twitter
                    window.open(`https://twitter.com/intent/tweet?url=${encodeURIComponent(productUrl)}&text=${encodeURIComponent(productTitle)}`, '_blank', 'width=600,height=400');
                    showNotification('Opening Twitter share...', 'info');
                    break;
                case 2: // WhatsApp
                    window.open(`https://api.whatsapp.com/send?text=${encodeURIComponent(productTitle + ' ' + productUrl)}`, '_blank');
                    showNotification('Opening WhatsApp...', 'info');
                    break;
                case 3: // Copy Link
                    navigator.clipboard.writeText(productUrl).then(() => {
                        showNotification('Link copied to clipboard!', 'success');
                    }).catch(() => {
                        showNotification('Failed to copy link', 'error');
                    });
                    break;
            }
        });
    });
    
    // ============ Review Actions ============
    const reviewActionBtns = document.querySelectorAll('.review-action-btn');
    
    reviewActionBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
            
            // Toggle helpful state
            if (!this.classList.contains('helpful-active')) {
                this.classList.add('helpful-active');
                this.style.borderColor = '#D4AF37';
                this.style.color = '#D4AF37';
                showNotification('Thanks for your feedback!', 'success');
            }
        });
    });
    
    // ============ Write Review Button ============
    const writeReviewBtn = document.querySelector('.btn-write-review');
    
    if (writeReviewBtn) {
        writeReviewBtn.addEventListener('click', function() {
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = 'scale(1)';
            }, 100);
            
            showNotification('Review form coming soon!', 'info');
            // Here you would open a review modal or redirect to review page
        });
    }
    
    // ============ Product Card Click Handler ============
    const productCards = document.querySelectorAll('.product-card');
    
    productCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Don't trigger if clicking wishlist button
            if (e.target.closest('.wishlist-btn')) {
                return;
            }
            
            const productId = this.getAttribute('data-id');
            window.location.href = `product-detail.php?id=${productId}`;
        });
    });
    
    // ============ Smooth Scroll for Breadcrumb and Links ============
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
    
    // ============ Rating Bar Animation on Scroll ============
    const ratingBars = document.querySelectorAll('.rating-bar-fill');
    
    const animateRatingBars = () => {
        ratingBars.forEach(bar => {
            const barPosition = bar.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.3;
            
            if (barPosition < screenPosition) {
                bar.style.width = bar.parentElement.parentElement.querySelector('.rating-bar-percent').textContent;
            }
        });
    };
    
    // Initial check
    animateRatingBars();
    
    // Animate on scroll
    window.addEventListener('scroll', animateRatingBars);
    
    // ============ Notification System ============
    function showNotification(message, type = 'info') {
        // Remove existing notification if any
        const existingNotification = document.querySelector('.custom-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `custom-notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <i class="fas fa-${getNotificationIcon(type)}"></i>
                <span>${message}</span>
            </div>
        `;
        
        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${getNotificationColor(type)};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            z-index: 10001;
            animation: slideInRight 0.3s ease;
            border: 2px solid ${getNotificationBorderColor(type)};
            max-width: 300px;
        `;
        
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 3000);
    }
    
    function getNotificationIcon(type) {
        switch(type) {
            case 'success': return 'check-circle';
            case 'error': return 'exclamation-circle';
            case 'warning': return 'exclamation-triangle';
            case 'info': return 'info-circle';
            default: return 'info-circle';
        }
    }
    
    function getNotificationColor(type) {
        switch(type) {
            case 'success': return '#28a745';
            case 'error': return '#dc3545';
            case 'warning': return '#ffc107';
            case 'info': return '#D4AF37';
            default: return '#D4AF37';
        }
    }
    
    function getNotificationBorderColor(type) {
        switch(type) {
            case 'success': return '#20c997';
            case 'error': return '#ff6b6b';
            case 'warning': return '#ff9800';
            case 'info': return '#B8941F';
            default: return '#B8941F';
        }
    }
    
    // ============ Add Animation Styles ============
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
        
        .notification-content {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 600;
        }
        
        .notification-content i {
            font-size: 1.2rem;
        }
        
        .custom-notification {
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .custom-notification:hover {
            transform: scale(1.05);
        }
    `;
    document.head.appendChild(style);
    
    // ============ Lazy Loading for Images ============
    const images = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });
    
    images.forEach(img => imageObserver.observe(img));
    
    // ============ Sticky Add to Cart on Scroll (Mobile) ============
    if (window.innerWidth < 768) {
        const actionButtons = document.querySelector('.action-buttons');
        let lastScrollTop = 0;
        
        window.addEventListener('scroll', () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            const actionButtonsPosition = actionButtons.getBoundingClientRect();
            
            if (scrollTop > lastScrollTop && actionButtonsPosition.top < 0) {
                // Scrolling down and action buttons are out of view
                if (!actionButtons.classList.contains('sticky-mobile')) {
                    createStickyButtons();
                }
            } else if (actionButtonsPosition.top > 0) {
                // Action buttons are in view
                removeStickyButtons();
            }
            
            lastScrollTop = scrollTop;
        });
        
        function createStickyButtons() {
            const existingSticky = document.querySelector('.sticky-cart-mobile');
            if (existingSticky) return;
            
            const stickyDiv = document.createElement('div');
            stickyDiv.className = 'sticky-cart-mobile';
            stickyDiv.innerHTML = `
                <button class="btn-add-to-cart-sticky" onclick="document.getElementById('addToCartBtn').click()">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Add to Cart</span>
                </button>
                <button class="btn-buy-now-sticky" onclick="document.getElementById('buyNowBtn').click()">
                    <i class="fas fa-bolt"></i>
                    <span>Buy Now</span>
                </button>
            `;
            
            stickyDiv.style.cssText = `
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                padding: 0.75rem;
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 0.5rem;
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.1);
                z-index: 1000;
                border-top: 2px solid #D4AF37;
                animation: slideUp 0.3s ease;
            `;
            
            document.body.appendChild(stickyDiv);
            
            // Style the buttons
            const addBtn = stickyDiv.querySelector('.btn-add-to-cart-sticky');
            const buyBtn = stickyDiv.querySelector('.btn-buy-now-sticky');
            
            const buttonStyle = `
                padding: 0.75rem 1rem;
                border: 2px solid;
                border-radius: 8px;
                font-weight: 700;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                transition: all 0.3s;
                font-size: 0.9rem;
            `;
            
            addBtn.style.cssText = buttonStyle + `
                background: white;
                border-color: #D4AF37;
                color: #D4AF37;
            `;
            
            buyBtn.style.cssText = buttonStyle + `
                background: linear-gradient(135deg, #D4AF37, #B8941F);
                border-color: #B8941F;
                color: white;
            `;
        }
        
        function removeStickyButtons() {
            const stickyDiv = document.querySelector('.sticky-cart-mobile');
            if (stickyDiv) {
                stickyDiv.style.animation = 'slideDown 0.3s ease';
                setTimeout(() => {
                    stickyDiv.remove();
                }, 300);
            }
        }
        
        // Add slide animations
        const slideStyle = document.createElement('style');
        slideStyle.textContent = `
            @keyframes slideUp {
                from {
                    transform: translateY(100%);
                }
                to {
                    transform: translateY(0);
                }
            }
            
            @keyframes slideDown {
                from {
                    transform: translateY(0);
                }
                to {
                    transform: translateY(100%);
                }
            }
        `;
        document.head.appendChild(slideStyle);
    }
    
    // ============ Price Animation on Load ============
    const currentPrice = document.querySelector('.current-price');
    if (currentPrice) {
        const finalPrice = currentPrice.textContent;
        const priceValue = parseFloat(finalPrice.replace(/[^0-9.]/g, ''));
        let currentValue = 0;
        const increment = priceValue / 50;
        
        const animatePrice = setInterval(() => {
            currentValue += increment;
            if (currentValue >= priceValue) {
                currentValue = priceValue;
                clearInterval(animatePrice);
            }
            currentPrice.textContent = `${currentValue.toFixed(2)}`;
        }, 20);
    }
    
    // ============ Stock Warning Animation ============
    const stockStatus = document.querySelector('.stock-low');
    if (stockStatus) {
        setInterval(() => {
            stockStatus.style.transform = 'scale(1.05)';
            setTimeout(() => {
                stockStatus.style.transform = 'scale(1)';
            }, 200);
        }, 2000);
    }
    
    // ============ Feature Items Hover Effect ============
    const featureItems = document.querySelectorAll('.feature-item-detailed');
    
    featureItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
    });
    
    // ============ Keyboard Navigation for Quantity ============
    if (quantityInput) {
        document.addEventListener('keydown', (e) => {
            if (document.activeElement === quantityInput) {
                if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    increaseBtn.click();
                } else if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    decreaseBtn.click();
                }
            }
        });
    }
    
    // ============ Initialize Tooltips ============
    const tooltipElements = document.querySelectorAll('[title]');
    
    tooltipElements.forEach(element => {
        element.addEventListener('mouseenter', function() {
            const title = this.getAttribute('title');
            if (!title) return;
            
            const tooltip = document.createElement('div');
            tooltip.className = 'custom-tooltip';
            tooltip.textContent = title;
            tooltip.style.cssText = `
                position: absolute;
                background: #2c2c2c;
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 6px;
                font-size: 0.85rem;
                z-index: 10002;
                white-space: nowrap;
                pointer-events: none;
                opacity: 0;
                transition: opacity 0.3s;
                border: 2px solid #D4AF37;
            `;
            
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.top = `${rect.top - tooltip.offsetHeight - 10}px`;
            tooltip.style.left = `${rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2)}px`;
            
            setTimeout(() => {
                tooltip.style.opacity = '1';
            }, 10);
            
            this._tooltip = tooltip;
            this.removeAttribute('title');
            this._originalTitle = title;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.style.opacity = '0';
                setTimeout(() => {
                    this._tooltip.remove();
                }, 300);
            }
            if (this._originalTitle) {
                this.setAttribute('title', this._originalTitle);
            }
        });
    });
    
    // ============ Console Welcome Message ============
    console.log('%c🛒 Product Detail Page Loaded Successfully! ', 'background: #D4AF37; color: white; font-size: 16px; padding: 10px; border-radius: 5px;');
    console.log('%cAll interactive features are ready.', 'color: #B8941F; font-size: 12px;');
    
    // ============ Performance Monitoring ============
    window.addEventListener('load', () => {
        const loadTime = performance.now();
        console.log(`⚡ Page loaded in ${loadTime.toFixed(2)}ms`);
    });
    
});