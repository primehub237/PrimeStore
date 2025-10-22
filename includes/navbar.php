<!-- Navigation Component - nav.php -->
<style>
    .gold-text { color: #D4AF37; }
    .gold-border { border-color: #D4AF37; }
    .gold-bg { background-color: #D4AF37; }
    .gold-hover:hover { background-color: #C5A028; }
</style>

<!-- Navigation Component -->
<nav class="bg-white border-b-2 gold-border shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <!-- Main Navigation Row -->
        <div class="flex items-center justify-between py-4">
            
            <!-- Logo Section -->
            <div class="flex items-center space-x-4">
                <!-- Mobile Menu Toggle -->
                <button id="mobileMenuBtn" class="lg:hidden text-gray-700 hover:gold-text transition-colors">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
                
                <!-- Logo -->
                <a href="index.php" class="flex items-center space-x-2">
                    <div class="gold-bg text-white w-10 h-10 rounded-full flex items-center justify-center font-bold text-xl">
                        P
                    </div>
                    <span class="text-2xl font-bold gold-text hidden sm:block">PrimeStore</span>
                </a>
            </div>

            <!-- Search Bar (Desktop & Tablet) -->
            <div class="hidden md:flex flex-1 max-w-2xl mx-4 lg:mx-8">
                <div class="relative w-full">
                    <input 
                        type="text" 
                        placeholder="Search for anything..." 
                        class="w-full px-4 py-2 border-2 gold-border rounded-full focus:outline-none focus:ring-2 focus:ring-yellow-400 pr-12"
                    >
                    <button class="absolute right-2 top-1/2 -translate-y-1/2 gold-bg text-white w-8 h-8 rounded-full gold-hover transition-colors flex items-center justify-center">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center space-x-3 sm:space-x-6">
                <!-- Search Icon (Mobile Only) -->
                <button id="mobileSearchBtn" class="md:hidden text-gray-700 hover:gold-text transition-colors">
                    <i class="fas fa-search text-xl"></i>
                </button>

                <!-- Sign In -->
                <a href="signin.php" class="hidden sm:flex items-center space-x-1 text-gray-700 hover:gold-text transition-colors">
                    <i class="fas fa-user"></i>
                    <span class="hidden lg:inline font-medium">Sign In</span>
                </a>

                <!-- Favorites -->
                <a href="favorites.php" class="relative text-gray-700 hover:gold-text transition-colors">
                    <i class="fas fa-heart text-xl"></i>
                    <span class="absolute -top-2 -right-2 gold-bg text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">3</span>
                </a>

                <!-- Gift Registry -->
                <a href="registry.php" class="hidden sm:block text-gray-700 hover:gold-text transition-colors">
                    <i class="fas fa-gift text-xl"></i>
                </a>

                <!-- Cart -->
                <a href="cart.php" class="relative text-gray-700 hover:gold-text transition-colors">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <span class="absolute -top-2 -right-2 gold-bg text-white text-xs w-5 h-5 rounded-full flex items-center justify-center font-bold">5</span>
                </a>
            </div>
        </div>

        <!-- Mobile Search Bar (Hidden by default) -->
        <div id="mobileSearchBar" class="md:hidden pb-4 hidden">
            <div class="relative">
                <input 
                    type="text" 
                    placeholder="Search for anything..." 
                    class="w-full px-4 py-2 border-2 gold-border rounded-full focus:outline-none focus:ring-2 focus:ring-yellow-400 pr-12"
                >
                <button class="absolute right-2 top-1/2 -translate-y-1/2 gold-bg text-white w-8 h-8 rounded-full gold-hover transition-colors flex items-center justify-center">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- Category Navigation (Desktop) -->
        <div class="hidden lg:flex items-center justify-center space-x-8 pb-4 border-t border-gray-200 pt-4">
            <a href="gifts.php" class="flex items-center space-x-2 text-gray-700 hover:gold-text transition-colors font-medium">
                <i class="fas fa-gift"></i>
                <span>Gifts</span>
            </a>
            <a href="halloween.php" class="flex items-center space-x-2 text-gray-700 hover:gold-text transition-colors font-medium">
                <i class="fas fa-ghost"></i>
                <span>Electronics</span>
            </a>
            <a href="home.php" class="flex items-center space-x-2 text-gray-700 hover:gold-text transition-colors font-medium">
                <i class="fas fa-home"></i>
                <span>Home Favorites</span>
            </a>
            <a href="fashion.php" class="flex items-center space-x-2 text-gray-700 hover:gold-text transition-colors font-medium">
                <i class="fas fa-tshirt"></i>
                <span>Fashion Finds</span>
            </a>
            <a href="registry.php" class="flex items-center space-x-2 text-gray-700 hover:gold-text transition-colors font-medium">
                <i class="fa-solid fa-dumbbell"></i>
                <span>Sports</span>
            </a>
            <a href="registry.php" class="flex items-center space-x-2 text-gray-700 hover:gold-text transition-colors font-medium">
                <i class="fa-regular fa-heart"></i>
                <span>Health & Beauty</span>
            </a>
            <a href="registry.php" class="flex items-center space-x-2 text-gray-700 hover:gold-text transition-colors font-medium">
                <i class="fa-solid fa-computer"></i>
                <span>Computers</span>
            </a>
        </div>
    </div>
</nav>

<!-- Mobile Slide-out Menu -->
<div id="mobileMenu" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div id="menuPanel" class="bg-white h-full w-80 max-w-full transform -translate-x-full transition-transform duration-300 overflow-y-auto">
        <!-- Menu Header -->
        <div class="gold-bg text-white p-6 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <i class="fas fa-user-circle text-3xl"></i>
                <div>
                    <p class="font-bold">Hello!</p>
                    <a href="signin.php" class="text-sm underline">Sign in</a>
                </div>
            </div>
            <button id="closeMobileMenu" class="text-white hover:text-gray-200">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Menu Categories -->
        <div class="py-4">
            <a href="index.php" class="flex items-center space-x-3 px-6 py-3 text-gray-700 hover:bg-yellow-50 hover:gold-text transition-colors border-l-4 border-transparent hover:gold-border">
                <i class="fas fa-home w-6"></i>
                <span class="font-medium">Home</span>
            </a>
            <a href="gifts.php" class="flex items-center space-x-3 px-6 py-3 text-gray-700 hover:bg-yellow-50 hover:gold-text transition-colors border-l-4 border-transparent hover:gold-border">
                <i class="fas fa-gift w-6"></i>
                <span class="font-medium">Gifts</span>
            </a>
            <a href="halloween.php" class="flex items-center space-x-3 px-6 py-3 text-gray-700 hover:bg-yellow-50 hover:gold-text transition-colors border-l-4 border-transparent hover:gold-border">
                <i class="fas fa-ghost w-6"></i>
                <span class="font-medium">Halloween Favorites</span>
            </a>
            <a href="home.php" class="flex items-center space-x-3 px-6 py-3 text-gray-700 hover:bg-yellow-50 hover:gold-text transition-colors border-l-4 border-transparent hover:gold-border">
                <i class="fas fa-couch w-6"></i>
                <span class="font-medium">Home Favorites</span>
            </a>
            <a href="fashion.php" class="flex items-center space-x-3 px-6 py-3 text-gray-700 hover:bg-yellow-50 hover:gold-text transition-colors border-l-4 border-transparent hover:gold-border">
                <i class="fas fa-tshirt w-6"></i>
                <span class="font-medium">Fashion Finds</span>
            </a>
            <a href="registry.php" class="flex items-center space-x-3 px-6 py-3 text-gray-700 hover:bg-yellow-50 hover:gold-text transition-colors border-l-4 border-transparent hover:gold-border">
                <i class="fas fa-list-alt w-6"></i>
                <span class="font-medium">Registry</span>
            </a>
        </div>

        <div class="border-t border-gray-200 py-4">
            <a href="favorites.php" class="flex items-center space-x-3 px-6 py-3 text-gray-700 hover:bg-yellow-50 hover:gold-text transition-colors">
                <i class="fas fa-heart w-6"></i>
                <span class="font-medium">My Favorites</span>
            </a>
            <a href="orders.php" class="flex items-center space-x-3 px-6 py-3 text-gray-700 hover:bg-yellow-50 hover:gold-text transition-colors">
                <i class="fas fa-box w-6"></i>
                <span class="font-medium">Orders</span>
            </a>
            <a href="account.php" class="flex items-center space-x-3 px-6 py-3 text-gray-700 hover:bg-yellow-50 hover:gold-text transition-colors">
                <i class="fas fa-cog w-6"></i>
                <span class="font-medium">Account Settings</span>
            </a>
        </div>
    </div>
</div>

<script>
    // Mobile Menu Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    const menuPanel = document.getElementById('menuPanel');
    const closeMobileMenu = document.getElementById('closeMobileMenu');

    function openMenu() {
        mobileMenu.classList.remove('hidden');
        setTimeout(() => {
            menuPanel.classList.remove('-translate-x-full');
        }, 10);
    }

    function closeMenu() {
        menuPanel.classList.add('-translate-x-full');
        setTimeout(() => {
            mobileMenu.classList.add('hidden');
        }, 300);
    }

    mobileMenuBtn.addEventListener('click', openMenu);
    closeMobileMenu.addEventListener('click', closeMenu);
    
    // Close menu when clicking outside
    mobileMenu.addEventListener('click', (e) => {
        if (e.target === mobileMenu) {
            closeMenu();
        }
    });

    // Mobile Search Toggle
    const mobileSearchBtn = document.getElementById('mobileSearchBtn');
    const mobileSearchBar = document.getElementById('mobileSearchBar');

    mobileSearchBtn.addEventListener('click', () => {
        mobileSearchBar.classList.toggle('hidden');
    });

    // Close mobile menu on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
            closeMenu();
        }
    });
</script>