<?php
// pages/shop/cart.php
session_start();
include '../includes/header.php';

// Initialize cart data in session if not exists
if (!isset($_SESSION['cart_step'])) {
    $_SESSION['cart_step'] = 1;
}

// Sample cart data
$cartItems = [
    [
        'id' => 1,
        'name' => 'Cute worm baby toys',
        'price' => 45.20,
        'quantity' => 1,
        'age' => '1-2 yr',
        'gender' => 'Girl',
        'delivery' => '3 days',
        'image' => 'https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?w=200',
        'selected' => true
    ],
    [
        'id' => 2,
        'name' => 'Cute crab baby toys',
        'price' => 45.20,
        'quantity' => 1,
        'age' => '1-2 yr',
        'gender' => 'Girl',
        'delivery' => '3 days',
        'image' => 'https://images.unsplash.com/photo-1587588354456-ae376af71a25?w=200',
        'selected' => false
    ],
    [
        'id' => 3,
        'name' => 'Plush toys for babies',
        'price' => 45.20,
        'quantity' => 1,
        'age' => '1-2 yr',
        'gender' => 'Girl',
        'delivery' => '3 days',
        'image' => 'https://images.unsplash.com/photo-1530325553241-4f6e7690cf36?w=200',
        'selected' => false
    ],
    [
        'id' => 4,
        'name' => 'Cute snail baby toys',
        'price' => 16.20,
        'quantity' => 1,
        'age' => '1-2 yr',
        'gender' => 'Girl',
        'delivery' => '3 days',
        'image' => 'https://images.unsplash.com/photo-1581833971358-2c8b550f87b3?w=200',
        'selected' => false
    ]
];

// Store cart items in session
$_SESSION['cart_items'] = $cartItems;
$_SESSION['coupon_code'] = 'MAX500';
$_SESSION['coupon_discount'] = 2.50;

$currentStep = isset($_GET['step']) ? (int)$_GET['step'] : 1;
$_SESSION['cart_step'] = $currentStep;
?>

<link rel="stylesheet" href="../assets/css/cart.css">

<!-- Main Content -->
<main class="cart-main">
    <div class="cart-container">
        <!-- Progress Steps -->
        <div class="progress-steps">
            <div class="step <?php echo $currentStep >= 1 ? 'active' : ''; ?>" data-step="1">
                <div class="step-circle">1</div>
                <span class="step-label">Cart</span>
            </div>
            <div class="step-line <?php echo $currentStep > 1 ? 'completed' : ''; ?>"></div>
            <div class="step <?php echo $currentStep >= 2 ? 'active' : ''; ?> <?php echo $currentStep > 2 ? 'completed' : ''; ?>" data-step="2">
                <div class="step-circle">2</div>
                <span class="step-label">Address</span>
            </div>
            <div class="step-line <?php echo $currentStep > 2 ? 'completed' : ''; ?>"></div>
            <div class="step <?php echo $currentStep >= 3 ? 'active' : ''; ?>" data-step="3">
                <div class="step-circle">3</div>
                <span class="step-label">Payment</span>
            </div>
        </div>

        <!-- Step Container with Swipe Animation -->
        <div class="step-container">
            <!-- Step 1: Cart Items -->
            <div class="step-content <?php echo $currentStep === 1 ? 'active' : ''; ?>" id="step-1">
                <div class="cart-layout">
                    <!-- Cart Items Section -->
                    <section class="cart-items-section">
                        <!-- Selection Header -->
                        <div class="selection-header">
                            <label class="checkbox-container">
                                <input type="checkbox" id="selectAll" checked>
                                <span class="checkmark"></span>
                                <span class="selection-text"><span id="selectedCount">1</span>/<?php echo count($cartItems); ?> items selected</span>
                            </label>
                            <div class="selection-actions">
                                <button class="action-link" id="moveToWishlist">
                                    <i class="far fa-heart"></i> Move to wishlist
                                </button>
                                <button class="action-link remove-action" id="removeSelected">
                                    <i class="far fa-trash-alt"></i> Remove
                                </button>
                            </div>
                        </div>

                        <!-- Cart Items -->
                        <div class="cart-items-list">
                            <?php foreach($cartItems as $item): ?>
                            <div class="cart-item" data-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['price']; ?>">
                                <label class="checkbox-container">
                                    <input type="checkbox" class="item-checkbox" <?php echo $item['selected'] ? 'checked' : ''; ?>>
                                    <span class="checkmark"></span>
                                </label>

                                <div class="item-image">
                                    <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                </div>

                                <div class="item-details">
                                    <h3 class="item-name"><?php echo $item['name']; ?></h3>
                                    <div class="item-meta">
                                        <span class="meta-tag">
                                            <i class="fas fa-child"></i> <?php echo $item['age']; ?>
                                        </span>
                                        <span class="meta-tag">
                                            <i class="fas fa-venus"></i> <?php echo $item['gender']; ?>
                                        </span>
                                        <span class="meta-tag express">
                                            <i class="fas fa-shipping-fast"></i> Express delivery in <?php echo $item['delivery']; ?>
                                        </span>
                                    </div>
                                    
                                    <div class="item-footer">
                                        <span class="item-price">$<?php echo number_format($item['price'], 2); ?></span>
                                        <div class="quantity-controls">
                                            <button class="qty-btn qty-minus" data-action="decrease">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" class="qty-input" value="<?php echo $item['quantity']; ?>" min="1" max="99" readonly>
                                            <button class="qty-btn qty-plus" data-action="increase">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <button class="remove-item-btn" data-id="<?php echo $item['id']; ?>">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </section>

                    <!-- Order Summary Section -->
                    <aside class="order-summary-section">
                        <!-- Coupons -->
                        <div class="summary-card coupons-card">
                            <div class="card-header">
                                <i class="fas fa-ticket-alt"></i>
                                <h3>Coupons</h3>
                            </div>
                            <div class="coupon-applied">
                                <i class="fas fa-tag"></i>
                                <span class="coupon-code"><?php echo $_SESSION['coupon_code']; ?></span>
                                <button class="remove-coupon" id="removeCoupon">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Gifting -->
                        <div class="summary-card gifting-card">
                            <div class="card-header">
                                <h3>Gifting</h3>
                            </div>
                            <div class="gifting-content">
                                <div class="gifting-text">
                                    <p class="gifting-title">Buying for a loved one?</p>
                                    <p class="gifting-subtitle">Send personalized message at $20</p>
                                    <button class="add-gift-btn" id="addGiftWrap">
                                        <i class="fas fa-gift"></i> Add gift wrap
                                    </button>
                                </div>
                                <div class="gifting-icon">
                                    <i class="fas fa-gift"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Price Details -->
                        <div class="summary-card price-details-card">
                            <div class="card-header">
                                <h3>Price Details</h3>
                            </div>
                            <div class="price-details">
                                <div class="price-row">
                                    <span class="price-label">
                                        <span id="itemCount">1</span> item
                                    </span>
                                </div>
                                <div class="price-items" id="priceItems">
                                    <div class="price-item">
                                        <span>1 x Cute worm baby toys</span>
                                        <span>$45.20</span>
                                    </div>
                                </div>
                                <div class="price-row discount">
                                    <span class="price-label">Coupon discount</span>
                                    <span class="price-value discount-value">-$<?php echo number_format($_SESSION['coupon_discount'], 2); ?></span>
                                </div>
                                <div class="price-row">
                                    <span class="price-label">Delivery Charges</span>
                                    <span class="price-value free">Free Delivery</span>
                                </div>
                                <div class="price-divider"></div>
                                <div class="price-row total">
                                    <span class="price-label">Total Amount</span>
                                    <span class="price-value" id="totalAmount">$42.70</span>
                                </div>
                            </div>
                            <button class="place-order-btn" id="proceedToAddress">
                                <span>Continue to Address</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </aside>
                </div>
            </div>

            <!-- Step 2: Address - Load via AJAX -->
            <div class="step-content" id="step-2"></div>

            <!-- Step 3: Payment - Load via AJAX -->
            <div class="step-content" id="step-3"></div>
        </div>
    </div>
</main>

<!-- Toast Notification -->
<div class="toast" id="toast">
    <i class="fas fa-check-circle"></i>
    <span id="toastMessage">Item removed from cart</span>
</div>

<script src="../assets/js/cart-navigation.js"></script>
<script src="../assets/js/cart.js"></script>

<?php require_once '../includes/footer.php'; ?>