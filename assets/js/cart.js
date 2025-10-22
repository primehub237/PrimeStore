
// ============================================
// SHOPPING CART JAVASCRIPT
// Mobile-Optimized Interactive Functionality
// ============================================

// ============================================
// INITIALIZATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    initializeCart();
    setupEventListeners();
    updateCartSummary();
});

// ============================================
// STATE MANAGEMENT
// ============================================
let cartState = {
    items: [],
    selectedItems: [],
    couponCode: 'MAX500',
    couponDiscount: 2.50,
    deliveryCharge: 0,
    giftWrap: false,
    giftWrapPrice: 20.00
};

// ============================================
// INITIALIZE CART
// ============================================
function initializeCart() {
    // Load cart items from DOM
    const cartItems = document.querySelectorAll('.cart-item');
    cartState.items = Array.from(cartItems).map(item => ({
        id: item.dataset.id,
        price: parseFloat(item.dataset.price),
        quantity: parseInt(item.querySelector('.qty-input').value),
        selected: item.querySelector('.item-checkbox').checked
    }));
    
    // Update selected items
    updateSelectedItems();
}

// ============================================
// EVENT LISTENERS SETUP
// ============================================
function setupEventListeners() {
    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileNav = document.getElementById('mobileNav');
    
    if (mobileMenuToggle && mobileNav) {
        mobileMenuToggle.addEventListener('click', () => {
            mobileNav.classList.toggle('active');
            const icon = mobileMenuToggle.querySelector('i');
            icon.classList.toggle('fa-bars');
            icon.classList.toggle('fa-times');
        });
    }
    
    // Select all checkbox
    const selectAllCheckbox = document.getElementById('selectAll');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', handleSelectAll);
    }
    
    // Item checkboxes
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    itemCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', handleItemSelection);
    });
    
    // Quantity controls
    const qtyButtons = document.querySelectorAll('.qty-btn');
    qtyButtons.forEach(button => {
        button.addEventListener('click', handleQuantityChange);
    });
    
    // Remove item buttons
    const removeButtons = document.querySelectorAll('.remove-item-btn');
    removeButtons.forEach(button => {
        button.addEventListener('click', handleRemoveItem);
    });
    
    // Remove coupon
    const removeCouponBtn = document.getElementById('removeCoupon');
    if (removeCouponBtn) {
        removeCouponBtn.addEventListener('click', handleRemoveCoupon);
    }
    
    // Add gift wrap
    const addGiftBtn = document.getElementById('addGiftWrap');
    if (addGiftBtn) {
        addGiftBtn.addEventListener('click', handleAddGiftWrap);
    }
    
    // Place order
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    if (placeOrderBtn) {
        placeOrderBtn.addEventListener('click', handlePlaceOrder);
    }
    
    // Move to wishlist
    const moveToWishlistBtn = document.getElementById('moveToWishlist');
    if (moveToWishlistBtn) {
        moveToWishlistBtn.addEventListener('click', handleMoveToWishlist);
    }
    
    // Remove selected
    const removeSelectedBtn = document.getElementById('removeSelected');
    if (removeSelectedBtn) {
        removeSelectedBtn.addEventListener('click', handleRemoveSelected);
    }
}

// ============================================
// SELECT ALL HANDLER
// ============================================
function handleSelectAll(e) {
    const isChecked = e.target.checked;
    const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    
    itemCheckboxes.forEach(checkbox => {
        checkbox.checked = isChecked;
        const cartItem = checkbox.closest('.cart-item');
        const itemId = cartItem.dataset.id;
        const item = cartState.items.find(i => i.id === itemId);
        if (item) {
            item.selected = isChecked;
        }
    });
    
    updateSelectedItems();
    updateCartSummary();
}

// ============================================
// ITEM SELECTION HANDLER
// ============================================
function handleItemSelection(e) {
    const cartItem = e.target.closest('.cart-item');
    const itemId = cartItem.dataset.id;
    const item = cartState.items.find(i => i.id === itemId);
    
    if (item) {
        item.selected = e.target.checked;
    }
    
    updateSelectedItems();
    updateSelectAllCheckbox();
    updateCartSummary();
}

// ============================================
// QUANTITY CHANGE HANDLER
// ============================================
function handleQuantityChange(e) {
    const button = e.currentTarget;
    const action = button.dataset.action;
    const cartItem = button.closest('.cart-item');
    const qtyInput = cartItem.querySelector('.qty-input');
    const itemId = cartItem.dataset.id;
    
    let currentQty = parseInt(qtyInput.value);
    
    if (action === 'increase' && currentQty < 99) {
        currentQty++;
        animateButton(button, 'scale');
    } else if (action === 'decrease' && currentQty > 1) {
        currentQty--;
        animateButton(button, 'scale');
    }
    
    qtyInput.value = currentQty;
    
    // Update state
    const item = cartState.items.find(i => i.id === itemId);
    if (item) {
        item.quantity = currentQty;
    }
    
    updateCartSummary();
}

// ============================================
// REMOVE ITEM HANDLER
// ============================================
function handleRemoveItem(e) {
    const button = e.currentTarget;
    const cartItem = button.closest('.cart-item');
    const itemId = cartItem.dataset.id;
    const itemName = cartItem.querySelector('.item-name').textContent;
    
    // Add fade out animation
    cartItem.style.opacity = '0';
    cartItem.style.transform = 'translateX(20px)';
    
    setTimeout(() => {
        cartItem.remove();
        
        // Update state
        cartState.items = cartState.items.filter(i => i.id !== itemId);
        
        updateSelectedItems();
        updateCartSummary();
        updateSelectAllCheckbox();
        
        // Show toast
        showToast(`${itemName} removed from cart`);
        
        // Check if cart is empty
        checkEmptyCart();
    }, 300);
}

// ============================================
// REMOVE COUPON HANDLER
// ============================================
function handleRemoveCoupon() {
    cartState.couponCode = null;
    cartState.couponDiscount = 0;
    
    const couponCard = document.querySelector('.coupons-card');
    if (couponCard) {
        couponCard.style.opacity = '0';
        setTimeout(() => {
            couponCard.remove();
            updateCartSummary();
            showToast('Coupon removed');
        }, 300);
    }
}

// ============================================
// ADD GIFT WRAP HANDLER
// ============================================
function handleAddGiftWrap() {
    if (!cartState.giftWrap) {
        cartState.giftWrap = true;
        showToast('Gift wrap added for $20.00');
        
        // Update button
        const addGiftBtn = document.getElementById('addGiftWrap');
        if (addGiftBtn) {
            addGiftBtn.innerHTML = '<i class="fas fa-check"></i> Gift wrap added';
            addGiftBtn.style.background = '#10b981';
        }
        
        updateCartSummary();
    }
}

// ============================================
// PLACE ORDER HANDLER
// ============================================
function handlePlaceOrder() {
    const selectedCount = cartState.selectedItems.length;
    
    if (selectedCount === 0) {
        showToast('Please select at least one item', 'error');
        return;
    }
    
    // Add loading state
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    if (placeOrderBtn) {
        placeOrderBtn.classList.add('loading');
        placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        placeOrderBtn.disabled = true;
    }
    
    // Simulate order processing
    setTimeout(() => {
        showToast('Order placed successfully!', 'success');
        
        // In production, redirect to order confirmation page
        // window.location.href = 'order-confirmation.php';
        
        if (placeOrderBtn) {
            placeOrderBtn.classList.remove('loading');
            placeOrderBtn.innerHTML = '<span>Place order</span><i class="fas fa-arrow-right"></i>';
            placeOrderBtn.disabled = false;
        }
    }, 2000);
}

// ============================================
// MOVE TO WISHLIST HANDLER
// ============================================
function handleMoveToWishlist() {
    const selectedCount = cartState.selectedItems.length;
    
    if (selectedCount === 0) {
        showToast('Please select items to move', 'error');
        return;
    }
    
    // Remove selected items
    cartState.selectedItems.forEach(itemId => {
        const cartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
        if (cartItem) {
            cartItem.style.opacity = '0';
            setTimeout(() => cartItem.remove(), 300);
        }
    });
    
    // Update state
    cartState.items = cartState.items.filter(item => !cartState.selectedItems.includes(item.id));
    
    setTimeout(() => {
        updateSelectedItems();
        updateCartSummary();
        updateSelectAllCheckbox();
        showToast(`${selectedCount} item(s) moved to wishlist`);
        checkEmptyCart();
    }, 300);
}

// ============================================
// REMOVE SELECTED HANDLER
// ============================================
function handleRemoveSelected() {
    const selectedCount = cartState.selectedItems.length;
    
    if (selectedCount === 0) {
        showToast('Please select items to remove', 'error');
        return;
    }
    
    // Confirm deletion
    if (!confirm(`Remove ${selectedCount} selected item(s) from cart?`)) {
        return;
    }
    
    // Remove selected items
    cartState.selectedItems.forEach(itemId => {
        const cartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
        if (cartItem) {
            cartItem.style.opacity = '0';
            setTimeout(() => cartItem.remove(), 300);
        }
    });
    
    // Update state
    cartState.items = cartState.items.filter(item => !cartState.selectedItems.includes(item.id));
    
    setTimeout(() => {
        updateSelectedItems();
        updateCartSummary();
        updateSelectAllCheckbox();
        showToast(`${selectedCount} item(s) removed from cart`);
        checkEmptyCart();
    }, 300);
}

// ============================================
// UPDATE SELECTED ITEMS
// ============================================
function updateSelectedItems() {
    cartState.selectedItems = cartState.items
        .filter(item => item.selected)
        .map(item => item.id);
    
    // Update selected count
    const selectedCountEl = document.getElementById('selectedCount');
    if (selectedCountEl) {
        selectedCountEl.textContent = cartState.selectedItems.length;
    }
}

// ============================================
// UPDATE SELECT ALL CHECKBOX
// ============================================
function updateSelectAllCheckbox() {
    const selectAllCheckbox = document.getElementById('selectAll');
    if (!selectAllCheckbox) return;
    
    const totalItems = cartState.items.length;
    const selectedItems = cartState.selectedItems.length;
    
    if (selectedItems === 0) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    } else if (selectedItems === totalItems) {
        selectAllCheckbox.checked = true;
        selectAllCheckbox.indeterminate = false;
    } else {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = true;
    }
}

// ============================================
// UPDATE CART SUMMARY
// ============================================
function updateCartSummary() {
    // Calculate totals
    let subtotal = 0;
    let itemsList = [];
    
    cartState.selectedItems.forEach(itemId => {
        const item = cartState.items.find(i => i.id === itemId);
        if (item) {
            const itemTotal = item.price * item.quantity;
            subtotal += itemTotal;
            
            const cartItem = document.querySelector(`.cart-item[data-id="${itemId}"]`);
            const itemName = cartItem ? cartItem.querySelector('.item-name').textContent : 'Item';
            
            itemsList.push({
                name: itemName,
                quantity: item.quantity,
                price: itemTotal
            });
        }
    });
    
    const couponDiscount = cartState.couponDiscount || 0;
    const giftWrapCost = cartState.giftWrap ? cartState.giftWrapPrice : 0;
    const deliveryCost = cartState.deliveryCharge;
    const total = subtotal - couponDiscount + giftWrapCost + deliveryCost;

    // Update DOM elements
    const subtotalEl = document.getElementById('subtotal');
    const discountEl = document.getElementById('couponDiscount');
    const giftWrapEl = document.getElementById('giftWrapCost');
    const deliveryEl = document.getElementById('deliveryCost');
    const totalEl = document.getElementById('totalAmount');
    const itemCountEl = document.getElementById('itemCount');
    
    if (subtotalEl) {
        subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
    }
    if (discountEl) {
        discountEl.textContent = cartState.couponDiscount ? `-$${couponDiscount.toFixed(2)}` : '$0.00';
    }
    if (giftWrapEl) {
        giftWrapEl.textContent = cartState.giftWrap ? `$${giftWrapCost.toFixed(2)}` : '$0.00';
    }
    if (deliveryEl) {
        deliveryEl.textContent = `$${deliveryCost.toFixed(2)}`;
    }
    if (totalEl) {
        totalEl.textContent = `$${total.toFixed(2)}`;
    }
    if (itemCountEl) {
        itemCountEl.textContent = cartState.selectedItems.length;
    }

    // Update items list in summary
    const itemsListEl = document.getElementById('priceItems');
    if (itemsListEl) {
        itemsListEl.innerHTML = itemsList.map(item => `
            <div class="price-item">
                <span>${item.quantity} x ${item.name}</span>
                <span>$${item.price.toFixed(2)}</span>
            </div>
        `).join('');
    }
}

// ============================================
// ANIMATE BUTTON
// ============================================
function animateButton(button, animationType) {
    if (animationType === 'scale') {
        button.style.transform = 'scale(0.95)';
        setTimeout(() => {
            button.style.transform = 'scale(1)';
        }, 100);
    }
}

// ============================================
// SHOW TOAST
// ============================================
function showToast(message, type = 'info') {
    const existingToast = document.getElementById('toast');
    if (existingToast) {
        // Reuse the static toast element
        const toastMessage = existingToast.querySelector('#toastMessage');
        if (toastMessage) {
            toastMessage.textContent = message;
        }
        existingToast.className = `toast ${type}`;
        existingToast.style.opacity = '1';
        existingToast.style.transform = 'translateY(0)';
        
        // Hide and reset
        setTimeout(() => {
            existingToast.style.opacity = '0';
            existingToast.style.transform = 'translateY(20px)';
        }, 3000);
    } else {
        // Fallback to dynamic toast if static toast is removed
        const toastContainer = document.createElement('div');
        toastContainer.className = `toast ${type}`;
        toastContainer.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 24px;
            background: ${type === 'error' ? '#ef4444' : type === 'success' ? '#10b981' : '#3b82f6'};
            color: white;
            border-radius: 8px;
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.3s, transform 0.3s;
            z-index: 1000;
        `;
        toastContainer.innerHTML = `
            <i class="${type === 'error' ? 'fas fa-exclamation-circle' : 'fas fa-check-circle'}"></i>
            <span>${message}</span>
        `;
        document.body.appendChild(toastContainer);

        // Show toast
        setTimeout(() => {
            toastContainer.style.opacity = '1';
            toastContainer.style.transform = 'translateY(0)';
        }, 10);

        // Hide and remove toast
        setTimeout(() => {
            toastContainer.style.opacity = '0';
            toastContainer.style.transform = 'translateY(20px)';
            setTimeout(() => toastContainer.remove(), 300);
        }, 3000);
    }
}

// ============================================
// CHECK EMPTY CART
// ============================================
function checkEmptyCart() {
    const cartContainer = document.querySelector('.cart-items-list');
    if (cartState.items.length === 0 && cartContainer) {
        cartContainer.innerHTML = `
            <div class="empty-cart-message" style="text-align: center; padding: 20px;">
                <p>Your cart is empty</p>
                <a href="/shop" class="btn btn-primary">Continue Shopping</a>
            </div>
        `;
        updateCartSummary();
    }
}
