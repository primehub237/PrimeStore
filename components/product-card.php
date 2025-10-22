<!-- components/product-card.php -->
<div class="product-card bg-white rounded-xl border-2 border-gray-200 hover:gold-border overflow-hidden cursor-pointer group" 
     data-category="<?= htmlspecialchars($product['category'] ?? '') ?>" 
     data-id="<?= $product['id'] ?>">
    
    <div class="relative overflow-hidden">
        <img src="<?= htmlspecialchars($product['image']) ?>" 
             alt="<?= htmlspecialchars($product['name']) ?>" 
             class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300"
             loading="lazy">
        
        <?php if (isset($product['discount']) && $product['discount'] > 0): ?>
            <span class="absolute top-2 left-2 gold-bg text-white px-3 py-1 rounded-full text-xs font-bold">
                -<?= $product['discount'] ?>%
            </span>
        <?php endif; ?>
        
        <?php if (isset($product['hot']) && $product['hot']): ?>
            <span class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold animate-pulse">
                HOT
            </span>
        <?php endif; ?>
        
        <button class="wishlist-btn absolute bottom-2 right-2 bg-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg hover:gold-bg hover:text-white" 
                data-product-id="<?= $product['id'] ?>">
            <i class="far fa-heart"></i>
        </button>
    </div>
    
    <div class="p-4">
        <h3 class="text-sm font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:gold-text transition-colors">
            <?= htmlspecialchars($product['name']) ?>
        </h3>
        
        <?php if (isset($product['condition'])): ?>
            <p class="text-xs text-gray-500 mb-2"><?= htmlspecialchars($product['condition']) ?></p>
        <?php endif; ?>
        
        <div class="flex items-center justify-between">
            <div>
                <span class="text-xl font-bold gold-text">$<?= number_format($product['price'], 2) ?></span>
                <?php if (isset($product['originalPrice']) && $product['originalPrice'] > $product['price']): ?>
                    <span class="text-sm text-gray-400 line-through ml-2">
                        $<?= number_format($product['originalPrice'], 2) ?>
                    </span>
                <?php endif; ?>
            </div>
            <button class="add-to-cart-btn gold-bg text-white px-3 py-1 rounded-full text-xs font-semibold hover:gold-hover transition-colors" 
                    data-product-id="<?= $product['id'] ?>"
                    data-product-name="<?= htmlspecialchars($product['name']) ?>"
                    data-product-price="<?= $product['price'] ?>">
                <i class="fas fa-cart-plus"></i>
            </button>
        </div>
    </div>
</div>