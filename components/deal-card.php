<!-- components/deal-card.php -->
<div class="deal-card flex-shrink-0 bg-white rounded-xl border-2 border-gray-200 hover:gold-border overflow-hidden cursor-pointer group w-80 transition-all">
    <div class="relative overflow-hidden">
        <img src="<?= htmlspecialchars($deal['image']) ?>" 
             alt="<?= htmlspecialchars($deal['name']) ?>" 
             class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300"
             loading="lazy">
        
        <?php if (isset($deal['discount']) && $deal['discount'] > 0): ?>
            <span class="absolute top-2 left-2 bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                -<?= $deal['discount'] ?>% off
            </span>
        <?php endif; ?>
        
        <button class="wishlist-btn absolute top-2 right-2 bg-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg hover:gold-bg hover:text-white" 
                data-product-id="<?= $deal['id'] ?>">
            <i class="far fa-heart text-sm"></i>
        </button>
    </div>
    
    <div class="p-3">
        <h3 class="text-sm font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:gold-text transition-colors min-h-[40px]">
            <?= htmlspecialchars($deal['name']) ?>
        </h3>
        
        <?php if (isset($deal['rating'])): ?>
            <div class="flex items-center gap-1 mb-2">
                <?php 
                $fullStars = floor($deal['rating']);
                $hasHalfStar = ($deal['rating'] - $fullStars) >= 0.5;
                
                for ($i = 0; $i < $fullStars; $i++): ?>
                    <i class="fas fa-star text-yellow-400 text-xs"></i>
                <?php endfor; ?>
                
                <?php if ($hasHalfStar): ?>
                    <i class="fas fa-star-half-alt text-yellow-400 text-xs"></i>
                <?php endif; ?>
                
                <?php for ($i = 0; $i < (5 - ceil($deal['rating'])); $i++): ?>
                    <i class="far fa-star text-gray-300 text-xs"></i>
                <?php endfor; ?>
                
                <span class="text-xs text-gray-600 ml-1"><?= $deal['rating'] ?></span>
            </div>
        <?php endif; ?>
        
        <div class="mb-2">
            <div class="flex items-baseline gap-2">
                <span class="text-lg font-bold text-gray-900">USD <?= number_format($deal['price'], 2) ?></span>
                <?php if (isset($deal['originalPrice']) && $deal['originalPrice'] > $deal['price']): ?>
                    <span class="text-xs text-gray-400 line-through">
                        <?= number_format($deal['originalPrice'], 2) ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (isset($deal['shipping'])): ?>
            <p class="text-xs text-green-600 font-medium"><?= htmlspecialchars($deal['shipping']) ?></p>
        <?php endif; ?>
        
        <?php if (isset($deal['expires'])): ?>
            <div class="mt-2 flex items-center gap-1 text-xs text-gray-500">
                <i class="far fa-clock"></i>
                <span>Expires in <span class="countdown-timer font-semibold" data-time="<?= $deal['expires'] ?>"><?= $deal['expires'] ?></span></span>
            </div>
        <?php endif; ?>
    </div>
</div>