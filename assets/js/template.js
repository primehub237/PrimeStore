// assets/js/templates.js
// Reusable templates for rendering components

const Templates = {
    // Product Card Template
    productCard: (product) => `
        <div class="product-card bg-white rounded-xl border-2 border-gray-200 hover:gold-border overflow-hidden cursor-pointer group">
            <div class="relative overflow-hidden">
                <img src="${product.image}" alt="${product.name}" class="w-full h-48 object-cover group-hover:scale-110 transition-transform duration-300">
                ${product.discount ? `
                    <span class="absolute top-2 left-2 gold-bg text-white px-3 py-1 rounded-full text-xs font-bold">
                        -${product.discount}%
                    </span>
                ` : ''}
                ${product.hot ? `
                    <span class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold animate-pulse">
                        HOT
                    </span>
                ` : ''}
                <button class="absolute bottom-2 right-2 bg-white rounded-full p-2 opacity-0 group-hover:opacity-100 transition-opacity shadow-lg hover:gold-bg hover:text-white">
                    <i class="fas fa-heart"></i>
                </button>
            </div>
            <div class="p-4">
                <h3 class="text-sm font-semibold text-gray-800 mb-2 line-clamp-2 group-hover:gold-text transition-colors">
                    ${product.name}
                </h3>
                ${product.condition ? `
                    <p class="text-xs text-gray-500 mb-2">${product.condition}</p>
                ` : ''}
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xl font-bold gold-text">$${product.price}</span>
                        ${product.originalPrice ? `
                            <span class="text-sm text-gray-400 line-through ml-2">$${product.originalPrice}</span>
                        ` : ''}
                    </div>
                    <button class="gold-bg text-white px-3 py-1 rounded-full text-xs font-semibold hover:gold-hover transition-colors">
                        <i class="fas fa-cart-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    `,

    // Category Card Template
    categoryCard: (category) => `
        <div class="category-card bg-white rounded-xl border-2 border-gray-200 hover:gold-border overflow-hidden cursor-pointer group transition-all">
            <div class="relative h-32 overflow-hidden">
                <img src="${category.image}" alt="${category.name}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-2 left-2 text-white">
                    <i class="fas ${category.icon} text-2xl mb-1"></i>
                </div>
            </div>
            <div class="p-3 text-center">
                <h3 class="font-semibold text-gray-800 text-sm group-hover:gold-text transition-colors">
                    ${category.name}
                </h3>
            </div>
        </div>
    `,

    // Recently Viewed Section Template
    recentlyViewedSection: (products) => `
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Your Recently Viewed Items</h2>
            <a href="recently-viewed.php" class="gold-text hover:underline font-medium text-sm">See all</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            ${products.map(product => Templates.productCard(product)).join('')}
        </div>
    `,

    // Categories Section Template
    categoriesSection: (categories) => `
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">The future in your hands</h2>
            <p class="text-gray-600">Explore cutting-edge technology</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-4 w-100">
            ${categories.map(category => Templates.categoryCard(category)).join('')}
        </div>
    `,

    // Products Grid Template
    productsGrid: (products) => `
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Trending on PrimeStore</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            ${products.map(product => Templates.productCard(product)).join('')}
        </div>
    `,

    // Hot Deals Grid with special layout
    hotDealsGrid: (deals) => `
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Cell Phones & Smartphones</h2>
            <p class="text-gray-600 text-sm">Shop from the world's largest selection and best deals</p>
        </div>
        
        <!-- Hot Deals Carousel for Mobile -->
        <div class="lg:hidden overflow-x-auto pb-4 scrollbar-hide">
            <div class="flex gap-4" style="width: max-content;">
                ${deals.slice(0, 6).map(deal => `
                    <div class="w-64 flex-shrink-0">
                        ${Templates.productCard(deal)}
                    </div>
                `).join('')}
            </div>
        </div>

        <!-- Hot Deals Grid for Desktop -->
        <div class="hidden lg:grid grid-cols-4 gap-4">
            ${deals.slice(0, 8).map(deal => Templates.productCard(deal)).join('')}
        </div>

        <!-- Today's Deals Banner -->
        <div class="mt-8 bg-gray-900 rounded-xl p-6 flex items-center justify-between">
            <div class="text-white">
                <h3 class="text-2xl font-bold mb-2">Today's Deals</h3>
                <p class="text-gray-300">Shop incredible daily deals</p>
            </div>
            <button class="gold-bg text-white px-6 py-3 rounded-full font-semibold gold-hover transition-colors">
                Shop now
            </button>
        </div>
    `
};

// Helper function to render template into container
function render(containerId, template) {
    const container = document.getElementById(containerId);
    if (container) {
        container.innerHTML = template;
    }
}