<?php
// cart/address.php
if (!isset($_SESSION)) {
    session_start();
}

// Sample saved addresses
$savedAddresses = [
    [
        'id' => 1,
        'name' => 'John Doe',
        'phone' => '+1 (555) 123-4567',
        'address' => '123 Main Street, Apt 4B',
        'city' => 'New York',
        'state' => 'NY',
        'zip' => '10001',
        'country' => 'United States',
        'isDefault' => true,
        'type' => 'Home'
    ]
];

$_SESSION['saved_addresses'] = $savedAddresses;
?>

<script src="https://cdn.tailwindcss.com"></script>
<script>

</script>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Address Selection Section -->
        <div class="lg:col-span-2 space-y-4">
            <!-- Saved Addresses -->
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-primary"></i>
                        Select Delivery Address
                    </h2>
                    <button id="addNewAddressBtn" class="text-sm font-medium text-primary hover:text-primary-hover flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Add New
                    </button>
                </div>

                <div class="space-y-4" id="addressList">
                    <?php foreach($savedAddresses as $address): ?>
                    <div class="address-card border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-primary transition-all <?php echo $address['isDefault'] ? 'border-primary bg-blue-50' : ''; ?>" 
                         data-address-id="<?php echo $address['id']; ?>">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start gap-3 flex-1">
                                <input type="radio" name="selectedAddress" value="<?php echo $address['id']; ?>" 
                                       class="mt-1 w-5 h-5 text-primary focus:ring-primary" 
                                       <?php echo $address['isDefault'] ? 'checked' : ''; ?>>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="font-semibold text-gray-900"><?php echo $address['name']; ?></h3>
                                        <?php if($address['isDefault']): ?>
                                        <span class="px-2 py-0.5 bg-primary text-white text-xs font-medium rounded">Default</span>
                                        <?php endif; ?>
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs font-medium rounded"><?php echo $address['type']; ?></span>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-1"><?php echo $address['address']; ?></p>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <?php echo $address['city']; ?>, <?php echo $address['state']; ?> <?php echo $address['zip']; ?>
                                    </p>
                                    <p class="text-sm text-gray-600 mb-2">
                                        <i class="fas fa-phone text-gray-400 mr-2"></i><?php echo $address['phone']; ?>
                                    </p>
                                    <div class="flex items-center gap-4 mt-3">
                                        <button class="text-sm text-primary hover:text-primary-hover font-medium edit-address" data-address-id="<?php echo $address['id']; ?>">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                        <button class="text-sm text-red-600 hover:text-red-700 font-medium delete-address" data-address-id="<?php echo $address['id']; ?>">
                                            <i class="fas fa-trash mr-1"></i>Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Add New Address Form (Hidden by default) -->
                <div id="addAddressForm" class="hidden mt-6 p-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Add New Address</h3>
                    <form class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                <input type="text" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="John Doe">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                                <input type="tel" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="+1 (555) 123-4567">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                            <input type="text" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="123 Main Street, Apt 4B">
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">City *</label>
                                <input type="text" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="New York">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">State *</label>
                                <input type="text" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="NY">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">ZIP Code *</label>
                                <input type="text" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" placeholder="10001">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address Type</label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="addressType" value="Home" checked class="w-4 h-4 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Home</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="addressType" value="Work" class="w-4 h-4 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Work</span>
                                </label>
                                <label class="flex items-center gap-2">
                                    <input type="radio" name="addressType" value="Other" class="w-4 h-4 text-primary focus:ring-primary">
                                    <span class="text-sm text-gray-700">Other</span>
                                </label>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" id="makeDefault" class="w-4 h-4 text-primary focus:ring-primary rounded">
                            <label for="makeDefault" class="text-sm text-gray-700">Make this my default address</label>
                        </div>
                        <div class="flex gap-3">
                            <button type="submit" class="px-6 py-2.5 bg-secondary text-white font-medium rounded-lg hover:bg-secondary-light transition-colors">
                                Save Address
                            </button>
                            <button type="button" id="cancelAddAddress" class="px-6 py-2.5 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6 sticky top-20">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                
                <div class="space-y-3 mb-4 pb-4 border-b border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Items (<span id="summaryItemCount">1</span>)</span>
                        <span class="font-medium text-gray-900" id="summarySubtotal">$45.20</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Coupon Discount</span>
                        <span class="font-medium text-green-600">-$2.50</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Delivery Charges</span>
                        <span class="font-medium text-green-600">Free</span>
                    </div>
                </div>
                
                <div class="flex justify-between items-center mb-6">
                    <span class="text-lg font-bold text-gray-900">Total</span>
                    <span class="text-xl font-bold text-gray-900" id="summaryTotal">$42.70</span>
                </div>

                <div class="space-y-3">
                    <button id="proceedToPayment" class="w-full py-3 bg-['#facc15']-600 text-red font-semibold rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                        <span>Continue to Payment</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>
                    <button id="backToCart" class="w-full py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Cart</span>
                    </button>
                </div>

                <!-- Delivery Info -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-start gap-3 text-sm text-gray-600">
                        <i class="fas fa-shipping-fast text-primary mt-0.5"></i>
                        <div>
                            <p class="font-medium text-gray-900 mb-1">Express Delivery</p>
                            <p>Get your order delivered in 3 days</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Address page specific JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Add new address button
    const addNewAddressBtn = document.getElementById('addNewAddressBtn');
    const addAddressForm = document.getElementById('addAddressForm');
    const cancelAddAddress = document.getElementById('cancelAddAddress');
    
    if (addNewAddressBtn) {
        addNewAddressBtn.addEventListener('click', () => {
            addAddressForm.classList.toggle('hidden');
        });
    }
    
    if (cancelAddAddress) {
        cancelAddAddress.addEventListener('click', () => {
            addAddressForm.classList.add('hidden');
        });
    }

    // Address card selection
    const addressCards = document.querySelectorAll('.address-card');
    addressCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('button')) {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                
                // Update visual state
                addressCards.forEach(c => {
                    c.classList.remove('border-primary', 'bg-blue-50');
                    c.classList.add('border-gray-200');
                });
                this.classList.remove('border-gray-200');
                this.classList.add('border-primary', 'bg-blue-50');
            }
        });
    });

    // Edit address
    document.querySelectorAll('.edit-address').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const addressId = btn.dataset.addressId;
            // Show edit form or modal
            console.log('Edit address:', addressId);
        });
    });

    // Delete address
    document.querySelectorAll('.delete-address').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const addressId = btn.dataset.addressId;
            if (confirm('Are you sure you want to remove this address?')) {
                const card = btn.closest('.address-card');
                card.style.opacity = '0';
                card.style.transform = 'translateX(-20px)';
                setTimeout(() => card.remove(), 300);
            }
        });
    });
});
</script>