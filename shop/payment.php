<?php
// cart/payment.php
if (!isset($_SESSION)) {
    session_start();
}

// Sample saved payment methods
$savedCards = [
    [
        'id' => 1,
        'type' => 'Visa',
        'last4' => '4242',
        'expiry' => '12/25',
        'holderName' => 'John Doe',
        'isDefault' => true
    ],
    [
        'id' => 2,
        'type' => 'Mastercard',
        'last4' => '8888',
        'expiry' => '08/26',
        'holderName' => 'John Doe',
        'isDefault' => false
    ]
];

$_SESSION['saved_cards'] = $savedCards;
?>

<script src="https://cdn.tailwindcss.com"></script>
<script>

</script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Payment Methods Section -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Options -->
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2 mb-6">
                    <i class="fas fa-credit-card text-primary"></i>
                    Payment Method
                </h2>

                <!-- Payment Type Selection -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                    <button class="payment-type-btn active" data-type="card">
                        <i class="fas fa-credit-card text-2xl mb-2"></i>
                        <span class="font-medium">Card</span>
                    </button>
                    <button class="payment-type-btn" data-type="paypal">
                        <i class="fab fa-paypal text-2xl mb-2"></i>
                        <span class="font-medium">PayPal</span>
                    </button>
                    <button class="payment-type-btn" data-type="cash">
                        <i class="fas fa-money-bill-wave text-2xl mb-2"></i>
                        <span class="font-medium">Cash on Delivery</span>
                    </button>
                </div>

                <!-- Card Payment Section -->
                <div id="cardPaymentSection" class="payment-section">
                    <!-- Saved Cards -->
                    <div class="mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Saved Cards</h3>
                            <button id="addNewCardBtn" class="text-sm font-medium text-primary hover:text-primary-hover flex items-center gap-2">
                                <i class="fas fa-plus"></i>
                                Add New Card
                            </button>
                        </div>

                        <div class="space-y-3" id="savedCardsList">
                            <?php foreach($savedCards as $card): ?>
                            <div class="card-item border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-primary transition-all <?php echo $card['isDefault'] ? 'border-primary bg-yellow-50' : ''; ?>" 
                                 data-card-id="<?php echo $card['id']; ?>">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3 flex-1">
                                        <input type="radio" name="selectedCard" value="<?php echo $card['id']; ?>" 
                                               class="w-5 h-5 text-primary focus:ring-primary" 
                                               <?php echo $card['isDefault'] ? 'checked' : ''; ?>>
                                        <div class="flex items-center gap-3 flex-1">
                                            <?php if($card['type'] === 'Visa'): ?>
                                            <i class="fab fa-cc-visa text-3xl text-blue-600"></i>
                                            <?php elseif($card['type'] === 'Mastercard'): ?>
                                            <i class="fab fa-cc-mastercard text-3xl text-red-600"></i>
                                            <?php endif; ?>
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <p class="font-semibold text-gray-900">•••• •••• •••• <?php echo $card['last4']; ?></p>
                                                    <?php if($card['isDefault']): ?>
                                                    <span class="px-2 py-0.5 bg-primary text-secondary text-xs font-medium rounded">Default</span>
                                                    <?php endif; ?>
                                                </div>
                                                <p class="text-sm text-gray-600"><?php echo $card['holderName']; ?> • Expires <?php echo $card['expiry']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="text-red-600 hover:text-red-700 delete-card" data-card-id="<?php echo $card['id']; ?>">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Add New Card Form -->
                    <div id="addCardForm" class="hidden p-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Card</h3>
                        <form class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Card Number *</label>
                                <div class="relative">
                                    <input type="text" maxlength="19" placeholder="1234 5678 9012 3456" 
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent pl-12">
                                    <i class="fas fa-credit-card absolute left-4 top-3.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cardholder Name *</label>
                                <input type="text" placeholder="John Doe" 
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date *</label>
                                    <input type="text" placeholder="MM/YY" maxlength="5"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">CVV *</label>
                                    <input type="text" placeholder="123" maxlength="4"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" id="saveCard" class="w-4 h-4 text-primary focus:ring-primary rounded">
                                <label for="saveCard" class="text-sm text-gray-700">Save this card for future purchases</label>
                            </div>
                            <div class="flex gap-3">
                                <button type="submit" class="px-6 py-2.5 bg-secondary text-white font-medium rounded-lg hover:bg-secondary-light transition-colors">
                                    Add Card
                                </button>
                                <button type="button" id="cancelAddCard" class="px-6 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- PayPal Section -->
                <div id="paypalPaymentSection" class="payment-section hidden">
                    <div class="text-center py-12">
                        <i class="fab fa-paypal text-6xl text-blue-600 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Pay with PayPal</h3>
                        <p class="text-gray-600 mb-6">You'll be redirected to PayPal to complete your purchase</p>
                        <button class="px-8 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                            Continue with PayPal
                        </button>
                    </div>
                </div>

                <!-- Cash on Delivery Section -->
                <div id="cashPaymentSection" class="payment-section hidden">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <div class="flex items-start gap-4">
                            <i class="fas fa-money-bill-wave text-3xl text-green-600"></i>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Cash on Delivery</h3>
                                <p class="text-gray-600 mb-4">Pay with cash when your order is delivered to your doorstep.</p>
                                <div class="bg-white rounded-lg p-4 border border-green-200">
                                    <div class="flex items-center gap-2 text-sm text-gray-700">
                                        <i class="fas fa-info-circle text-green-600"></i>
                                        <span>Please keep exact change ready. Our delivery partner may not carry change.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Billing Address -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Billing Address</h3>
                        <button class="text-sm font-medium text-primary hover:text-primary-hover">Change</button>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="font-medium text-gray-900 mb-1">John Doe</p>
                        <p class="text-sm text-gray-600">123 Main Street, Apt 4B</p>
                        <p class="text-sm text-gray-600">New York, NY 10001</p>
                        <p class="text-sm text-gray-600">+1 (555) 123-4567</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Summary & Place Order -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 sticky top-20">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                
                <!-- Order Items Preview -->
                <div class="mb-4 pb-4 border-b border-gray-200">
                    <div class="flex items-center gap-3 mb-3">
                        <img src="https://images.unsplash.com/photo-1566576721346-d4a3b4eaeb55?w=60" 
                             class="w-16 h-16 rounded-lg object-cover" alt="Product">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">Cute worm baby toys</p>
                            <p class="text-xs text-gray-600">Qty: 1</p>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">$45.20</p>
                    </div>
                </div>

                <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium text-gray-900">$45.20</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Coupon Discount</span>
                        <span class="font-medium text-green-600">-$2.50</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Delivery Charges</span>
                        <span class="font-medium text-green-600">Free</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tax (Estimated)</span>
                        <span class="font-medium text-gray-900">$3.42</span>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mb-6">
                    <span class="text-lg font-bold text-gray-900">Total</span>
                    <span class="text-xl font-bold text-gray-900">$46.12</span>
                </div>

                <div class="space-y-3">
                    <button id="placeOrderBtn" class="w-full py-3 bg-secondary text-white font-semibold rounded-lg hover:bg-secondary-light transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-lock"></i>
                        <span>Place Secure Order</span>
                    </button>
                    <button id="backToAddress" class="w-full py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Address</span>
                    </button>
                </div>

                <!-- Security Badge -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-center gap-2 text-sm text-gray-600">
                        <i class="fas fa-shield-alt text-green-600"></i>
                        <span>Secure SSL Encrypted Payment</span>
                    </div>
                    <div class="flex items-center justify-center gap-4 mt-3">
                        <i class="fab fa-cc-visa text-2xl text-gray-400"></i>
                        <i class="fab fa-cc-mastercard text-2xl text-gray-400"></i>
                        <i class="fab fa-cc-paypal text-2xl text-gray-400"></i>
                        <i class="fab fa-cc-amex text-2xl text-gray-400"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.payment-type-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 1.5rem 1rem;
    border: 2px solid #e5e7eb;
    border-radius: 0.75rem;
    background: white;
    transition: all 0.3s;
    cursor: pointer;
}

.payment-type-btn:hover {
    border-color: #facc15;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.payment-type-btn.active {
    border-color: #facc15;
    background: #fef3c7;
}

.payment-section {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Payment page specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Payment type selection
    const paymentTypeBtns = document.querySelectorAll('.payment-type-btn');
    const paymentSections = {
        card: document.getElementById('cardPaymentSection'),
        paypal: document.getElementById('paypalPaymentSection'),
        cash: document.getElementById('cashPaymentSection')
    };

    paymentTypeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;
            
            // Update active state
            paymentTypeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Show corresponding section
            Object.values(paymentSections).forEach(section => section.classList.add('hidden'));
            paymentSections[type].classList.remove('hidden');
        });
    });

    // Add new card
    const addNewCardBtn = document.getElementById('addNewCardBtn');
    const addCardForm = document.getElementById('addCardForm');
    const cancelAddCard = document.getElementById('cancelAddCard');
    
    if (addNewCardBtn) {
        addNewCardBtn.addEventListener('click', () => {
            addCardForm.classList.toggle('hidden');
        });
    }
    
    if (cancelAddCard) {
        cancelAddCard.addEventListener('click', () => {
            addCardForm.classList.add('hidden');
        });
    }

    // Card selection
    const cardItems = document.querySelectorAll('.card-item');
    cardItems.forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('button')) {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Update visual state
                cardItems.forEach(c => {
                    c.classList.remove('border-primary', 'bg-yellow-50');
                    c.classList.add('border-gray-200');
                });
                this.classList.remove('border-gray-200');
                this.classList.add('border-primary', 'bg-yellow-50');
            }
        });
    });

    // Delete card
    document.querySelectorAll('.delete-card').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (confirm('Are you sure you want to remove this card?')) {
                const card = btn.closest('.card-item');
                card.style.opacity = '0';
                card.style.transform = 'translateX(-20px)';
                setTimeout(() => card.remove(), 300);
            }
        });
    });

    // Card number formatting
    const cardNumberInput = addCardForm?.querySelector('input[placeholder="1234 5678 9012 3456"]');
    if (cardNumberInput) {
        cardNumberInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\s/g, '');
            let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
            e.target.value = formattedValue;
        });
    }

    // Expiry date formatting
    const expiryInput = addCardForm?.querySelector('input[placeholder="MM/YY"]');
    if (expiryInput) {
        expiryInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\//g, '');
            if (value.length >= 2) {
                e.target.value = value.slice(0, 2) + '/' + value.slice(2, 4);
            } else {
                e.target.value = value;
            }
        });
    }

    // Place Order
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    if (placeOrderBtn) {
        placeOrderBtn.addEventListener('click', function() {
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i><span>Processing...</span>';
            this.disabled = true;
            
            // Simulate order processing
            setTimeout(() => {
                alert('Order placed successfully! 🎉');
                // Redirect to order confirmation
                // window.location.href = 'order-confirmation.php';
            }, 2000);
        });
    }
});
</script>