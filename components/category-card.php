<!-- components/category-card.php -->
<a href="<?= htmlspecialchars($category['link'] ?? 'category.php?id=' . $category['id']) ?>" 
   class="category-card bg-white rounded-xl border-2 border-gray-200 hover:gold-border overflow-hidden cursor-pointer group transition-all block">
    
    <div class="relative h-32 overflow-hidden">
        <img src="<?= htmlspecialchars($category['image']) ?>" 
             alt="<?= htmlspecialchars($category['name']) ?>" 
             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
             loading="lazy">
        
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
        
        <div class="absolute bottom-2 left-2 text-white">
            <i class="fas <?= htmlspecialchars($category['icon']) ?> text-2xl mb-1"></i>
        </div>
    </div>
    
    <div class="p-3 text-center">
        <h3 class="font-semibold text-gray-800 text-sm group-hover:gold-text transition-colors">
            <?= htmlspecialchars($category['name']) ?>
        </h3>
    </div>
</a>