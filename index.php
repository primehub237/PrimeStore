<?php
// index.php
include 'includes/header.php';
include 'includes/data.php';
?>

<!-- Main Content -->
<main>
    <!-- Hero Carousel Section -->
    <section class="container mx-auto px-4 mt-6">
        <div class="relative rounded-2xl overflow-hidden">
            <!-- Carousel Container -->
            <div id="heroCarousel" class="relative">
                <!-- Slide 1 - Build Collection -->
                <div class="carousel-slide active bg-gradient-to-r from-yellow-400 via-yellow-500 to-amber-500 rounded-2xl p-8 md:p-12">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div class="max-w-xl mb-6 md:mb-0 text-white">
                            <h1 class="text-3xl md:text-5xl font-bold mb-4">Build an elite collection</h1>
                            <p class="text-lg mb-6 opacity-90">Choose your next adventure from thousands of finds.</p>
                            <button class="bg-gray-900 text-white px-8 py-3 rounded-full font-semibold hover:bg-gray-800 transition-colors">
                                Start your journey
                            </button>
                        </div>
                        <div class="flex gap-8 items-center">
                            <div class="text-center">
                                <div class="bg-white rounded-2xl p-6 shadow-xl mb-3 transform hover:scale-105 transition-transform">
                                    <img src="https://images.unsplash.com/photo-1612036782180-6f0b6cd846fe?w=200" alt="Trading cards" class="w-32 h-40 object-cover rounded-lg">
                                </div>
                                <p class="text-white font-bold flex items-center justify-center gap-2">
                                    Trading cards <i class="fas fa-chevron-right text-sm"></i>
                                </p>
                            </div>
                            <div class="text-center hidden md:block">
                                <div class="bg-white rounded-2xl p-6 shadow-xl mb-3 transform hover:scale-105 transition-transform">
                                    <img src="https://images.unsplash.com/photo-1558060370-d644479cb6f7?w=200" alt="Toys" class="w-32 h-40 object-cover rounded-lg">
                                </div>
                                <p class="text-white font-bold flex items-center justify-center gap-2">
                                    Toys <i class="fas fa-chevron-right text-sm"></i>
                                </p>
                            </div>
                            <div class="text-center hidden lg:block">
                                <div class="bg-white rounded-2xl p-6 shadow-xl mb-3 transform hover:scale-105 transition-transform">
                                    <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=200" alt="Sports cards" class="w-32 h-40 object-cover rounded-lg">
                                </div>
                                <p class="text-white font-bold flex items-center justify-center gap-2">
                                    Sports cards <i class="fas fa-chevron-right text-sm"></i>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 - Tech Deals -->
                <div class="carousel-slide bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500 rounded-2xl p-8 md:p-12">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div class="max-w-xl mb-6 md:mb-0 text-white">
                            <h1 class="text-3xl md:text-5xl font-bold mb-4">Tech at your fingertips</h1>
                            <p class="text-lg mb-6 opacity-90">Discover the latest gadgets and electronics.</p>
                            <button class="bg-white text-purple-600 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition-colors">
                                Explore tech
                            </button>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div class="bg-white rounded-xl p-4 shadow-xl transform hover:scale-105 transition-transform">
                                <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=150" alt="Headphones" class="w-full h-24 object-cover rounded-lg mb-2">
                                <p class="text-sm font-semibold text-gray-800">Headphones</p>
                            </div>
                            <div class="bg-white rounded-xl p-4 shadow-xl transform hover:scale-105 transition-transform">
                                <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=150" alt="Watches" class="w-full h-24 object-cover rounded-lg mb-2">
                                <p class="text-sm font-semibold text-gray-800">Watches</p>
                            </div>
                            <div class="bg-white rounded-xl p-4 shadow-xl transform hover:scale-105 transition-transform hidden md:block">
                                <img src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=150" alt="Phones" class="w-full h-24 object-cover rounded-lg mb-2">
                                <p class="text-sm font-semibold text-gray-800">Phones</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 - Fashion -->
                <div class="carousel-slide bg-gradient-to-r from-rose-400 via-pink-500 to-purple-500 rounded-2xl p-8 md:p-12">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div class="max-w-xl mb-6 md:mb-0 text-white">
                            <h1 class="text-3xl md:text-5xl font-bold mb-4">Fashion that speaks</h1>
                            <p class="text-lg mb-6 opacity-90">Elevate your style with premium collections.</p>
                            <button class="bg-white text-rose-600 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition-colors">
                                Shop fashion
                            </button>
                        </div>
                        <div class="w-full md:w-1/2">
                            <img src="https://images.unsplash.com/photo-1483985988355-763728e1935b?w=500" alt="Fashion" class="rounded-2xl w-full h-64 object-cover shadow-2xl">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Arrows -->
            <button id="prevSlide" class="carousel-arrow absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 w-12 h-12 rounded-full shadow-xl flex items-center justify-center transition-all hover:scale-110 z-10">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button id="nextSlide" class="carousel-arrow absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-gray-800 w-12 h-12 rounded-full shadow-xl flex items-center justify-center transition-all hover:scale-110 z-10">
                <i class="fas fa-chevron-right"></i>
            </button>

            <!-- Dots Indicator -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                <button class="carousel-dot active w-3 h-3 rounded-full bg-white transition-all" data-slide="0"></button>
                <button class="carousel-dot w-3 h-3 rounded-full bg-white/50 transition-all" data-slide="1"></button>
                <button class="carousel-dot w-3 h-3 rounded-full bg-white/50 transition-all" data-slide="2"></button>
            </div>

            <!-- Pause/Play Button -->
            <button id="pauseCarousel" class="absolute top-4 right-4 bg-white/80 hover:bg-white text-gray-800 w-10 h-10 rounded-full shadow-lg flex items-center justify-center transition-all z-10">
                <i class="fas fa-pause text-sm"></i>
            </button>
        </div>
    </section>

    <!-- Shopping Made Easy Banner -->
    <section class="container mx-auto px-4 mt-8">
        <div class="bg-white rounded-xl border-2 gold-border p-6 flex flex-col sm:flex-row justify-between items-center shadow-sm">
            <div>
                <h2 class="text-xl font-bold text-gray-800 mb-1">Shopping made easy</h2>
                <p class="text-gray-600 text-sm">Shop, SEARCH, save or discover at PrimeStore free shopping.</p>
            </div>
            <button class="mt-4 sm:mt-0 bg-gray-800 text-white px-6 py-2 rounded-full font-semibold hover:bg-gray-700 transition-colors">
                Start now
            </button>
        </div>
    </section>

    <!-- Today's Big Deals Section -->
    <section class="container mx-auto px-4 mt-12">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-gray-800">Today's big deals</h2>
                <div class="hidden sm:flex items-center gap-2 text-sm text-gray-600">
                    <i class="far fa-clock"></i>
                    <span>Fresh deals in <span class="font-semibold countdown-main" id="mainCountdown">15:49:30</span></span>
                </div>
            </div>
            <div class="flex gap-2">
                <button id="dealsScrollLeft" class="deals-scroll-btn bg-white border-2 gold-border text-gray-800 w-10 h-10 rounded-full hover:gold-bg hover:text-white transition-all flex items-center justify-center">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="dealsScrollRight" class="deals-scroll-btn bg-white border-2 gold-border text-gray-800 w-10 h-10 rounded-full hover:gold-bg hover:text-white transition-all flex items-center justify-center">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
        
        <!-- Scrollable Deals Container -->
        <div id="dealsContainer" class="flex gap-4 overflow-x-auto pb-4 scrollbar-hide scroll-smooth">
            <?php foreach ($todaysBigDeals as $deal): ?>
                <?php include 'components/deal-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Editor's Picks Section -->
    <?php foreach ($editorsPicksSections as $section): ?>
    <section class="container mx-auto px-4 mt-12">
        <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl p-8 border-2 border-gray-200">
            <div class="grid md:grid-cols-2 gap-8 items-center mb-8">
                <div>
                    <p class="text-sm text-gray-600 font-semibold mb-2">Editors' Picks</p>
                    <h2 class="text-3xl font-bold text-gray-800 mb-3"><?= htmlspecialchars($section['title']) ?></h2>
                    <p class="text-gray-600 mb-6"><?= htmlspecialchars($section['subtitle']) ?></p>
                    <a href="<?= htmlspecialchars($section['buttonLink']) ?>" class="inline-block bg-gray-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors">
                        <?= htmlspecialchars($section['buttonText']) ?>
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <?php foreach (array_slice($section['products'], 0, 2) as $product): ?>
                        <div class="bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow cursor-pointer">
                            <img src="<?= htmlspecialchars($product['image']) ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>" 
                                 class="w-full h-48 object-cover hover:scale-110 transition-transform duration-300">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Product Grid -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php foreach ($section['products'] as $product): ?>
                    <div class="bg-white rounded-xl overflow-hidden shadow hover:shadow-lg transition-shadow cursor-pointer group">
                        <img src="<?= htmlspecialchars($product['image']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             class="w-full h-40 object-cover group-hover:scale-110 transition-transform duration-300">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endforeach; ?>

    <!-- Recently Viewed Section -->
    <?php if (!empty($recentlyViewed)): ?>
    <section class="container mx-auto px-4 mt-12">
        <div class="mb-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800">Your Recently Viewed Items</h2>
            <a href="recently-viewed.php" class="gold-text hover:underline font-medium text-sm">See all</a>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
            <?php foreach ($recentlyViewed as $product): ?>
                <?php include 'components/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Categories Section -->
    <section class="container mx-auto px-4 mt-12">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">The future in your hands</h2>
            <p class="text-gray-600">Explore cutting-edge technology</p>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-7 gap-4 ">
            <?php foreach ($categories as $category): ?>
                <?php include 'components/category-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Today's Deals Section -->
    <section class="container mx-auto px-4 mt-12">
        <div class="bg-gray-900 rounded-2xl p-8 text-white relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-yellow-500 opacity-10 rounded-full -mr-32 -mt-32"></div>
            <div class="relative z-10">
                <h2 class="text-3xl font-bold mb-2">Today's Deals</h2>
                <p class="text-gray-300 mb-6">Limited time offers</p>
                <button class="gold-bg text-white px-8 py-3 rounded-full font-semibold gold-hover transition-colors">
                    Shop now
                </button>
            </div>
        </div>
    </section>

    <!-- Products Grid Section with Filters -->
    <section class="container mx-auto px-4 mt-12">
        <!-- Filter Bar -->
        <div class="bg-white rounded-xl border-2 gold-border p-4 mb-6 sticky top-20 z-40 shadow-sm">
            <div class="flex flex-wrap gap-3 items-center">
                <span class="font-semibold text-gray-700">Filter by:</span>
                <button class="filter-btn active px-4 py-2 rounded-full border-2 border-gray-300 text-sm" data-filter="all">
                    All Items
                </button>
                <button class="filter-btn px-4 py-2 rounded-full border-2 border-gray-300 text-sm" data-filter="Electronics">
                    Electronics
                </button>
                <button class="filter-btn px-4 py-2 rounded-full border-2 border-gray-300 text-sm" data-filter="Fashion">
                    Fashion
                </button>
                <button class="filter-btn px-4 py-2 rounded-full border-2 border-gray-300 text-sm" data-filter="Home & Garden">
                    Home & Garden
                </button>
                <button class="filter-btn px-4 py-2 rounded-full border-2 border-gray-300 text-sm" data-filter="Collectibles">
                    Collectibles
                </button>
                <div class="ml-auto flex gap-2">
                    <button id="sortBtn" class="px-4 py-2 rounded-lg border-2 gold-border gold-text font-medium hover:gold-bg hover:text-white transition-colors">
                        <i class="fas fa-sort"></i> Sort
                    </button>
                </div>
            </div>
        </div>

        <!-- Hot Deals Section -->
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Cell Phones & Smartphones</h2>
            <p class="text-gray-600 text-sm">Shop from the world's largest selection and best deals</p>
        </div>

        <!-- Products Grid -->
        <div id="productsGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            <?php foreach ($hotDeals as $product): ?>
                <?php include 'components/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Trending Products Section -->
    <section class="container mx-auto px-4 mt-12">
        <div class="mb-4">
            <h2 class="text-2xl font-bold text-gray-800">Trending on PrimeStore</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 md:gap-6">
            <?php foreach ($trendingProducts as $product): ?>
                <?php include 'components/product-card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Promotional Banner -->
    <section class="container mx-auto px-4 mt-12 mb-16">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl overflow-hidden">
            <div class="flex flex-col md:flex-row items-center justify-between p-8 md:p-12">
                <div class="text-white mb-6 md:mb-0">
                    <h2 class="text-3xl md:text-4xl font-bold mb-3">There's a deal for you, too</h2>
                    <p class="text-lg mb-6 opacity-90">Don't miss a chance to save. Make every click count, starting here.</p>
                    <button class="bg-white text-orange-600 px-8 py-3 rounded-full font-bold hover:bg-gray-100 transition-colors">
                        Explore now
                    </button>
                </div>
                <div class="w-full md:w-1/3">
                    <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=400" alt="Deal" class="rounded-lg w-full h-64 object-cover">
                </div>
            </div>
        </div>
    </section>
    <section class="bg-[#FFF9E6] py-6 px-4 sm:px-6 md:px-8 lg:px-16">
  <div class="max-w-7xl mx-auto text-center">
    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-gray-800 mb-4">What is PrimeStore?</h1>
    <p class="text-base sm:text-lg text-gray-600 mb-8 sm:mb-12">Discover our innovative e-commerce platform.</p>
    
    <div class="grid grid-cols-1 gap-6 sm:gap-8 md:grid-cols-3 mb-8 sm:mb-12">
      <div class="text-left p-4">
        <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 sm:mb-4">A marketplace for businesses</h2>
        <p class="text-gray-600 text-sm sm:text-base">PrimeStore is a dynamic online marketplace where business owners can easily list their products, sell from our platform, and connect with clients worldwide. We're building a community that empowers entrepreneurs and fosters growth for businesses of all sizes, creating a positive impact together.</p>
      </div>
      <div class="text-left p-4">
        <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 sm:mb-4">Empower entrepreneurs</h2>
        <p class="text-gray-600 text-sm sm:text-base">There's no central warehouse – just countless business owners showcasing and selling the products they love. We make the whole process seamless, helping you connect directly with buyers to grow your business effortlessly.</p>
      </div>
      <div class="text-left p-4">
        <h2 class="text-xl sm:text-2xl font-semibold text-gray-800 mb-3 sm:mb-4">Peace of mind</h2>
        <p class="text-gray-600 text-sm sm:text-base">Your privacy and security are our highest priorities. Our dedicated team ensures a safe experience, and we're always ready to provide assistance whenever you need it.</p>
      </div>
    </div>
    
    <div class="mb-8 sm:mb-12">
      <p class="text-base sm:text-lg text-gray-600 mb-3 sm:mb-4">Have a question? Well, we've got some answers.</p>
      <a href="#" class="inline-block bg-white text-gray-800 font-semibold py-2 sm:py-3 px-4 sm:px-6 rounded-full border border-gray-300 hover:bg-gray-100 transition text-sm sm:text-base">Go to Help Center</a>
    </div>
  </div>
</section>

<section class=" py-6 px-4 sm:px-6 md:px-8 lg:px-16">
  <div class="max-w-7xl mx-auto text-center">
    <p class="text-base sm:text-lg text-gray-600 mb-4">Yes! Send me exclusive offers, unique product ideas, and personalized tips for shopping and selling on PrimeStore.</p>
    <form class="flex flex-col sm:flex-row justify-center items-center gap-3 sm:gap-4">
      <input type="email" placeholder="Enter your email" class="w-full sm:w-auto flex-1 py-2 sm:py-3 px-4 sm:px-6 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm sm:text-base">
      <button type="submit" class="bg-blue-600 text-white font-semibold py-2 sm:py-3 px-4 sm:px-6 rounded-full hover:bg-blue-700 transition text-sm sm:text-base">Subscribe</button>
    </form>
  </div>
</section>
</main>

<!-- Load JavaScript -->
<script src="assets/js/main.js"></script>

<!-- Carousel Inline Styles -->
<style>
.carousel-slide {
    display: none;
    opacity: 0;
    transition: opacity 0.6s ease-in-out;
}

.carousel-slide.active {
    display: block !important;
    opacity: 1 !important;
}

.carousel-arrow {
    backdrop-filter: blur(10px);
}
</style>

<!-- Carousel Script -->
<script>
(function() {
    let currentSlide = 0;
    let carouselInterval;
    let isCarouselPaused = false;

    function initCarousel() {
        const slides = document.querySelectorAll('.carousel-slide');
        const dots = document.querySelectorAll('.carousel-dot');
        const prevBtn = document.getElementById('prevSlide');
        const nextBtn = document.getElementById('nextSlide');
        const pauseBtn = document.getElementById('pauseCarousel');
        
        if (!slides || slides.length === 0) return;
        
        showSlide(0);
        startCarousel();
        
        if (prevBtn) {
            prevBtn.addEventListener('click', function(e) {
                e.preventDefault();
                changeSlide(-1);
                resetCarousel();
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', function(e) {
                e.preventDefault();
                changeSlide(1);
                resetCarousel();
            });
        }
        
        if (pauseBtn) {
            pauseBtn.addEventListener('click', function(e) {
                e.preventDefault();
                togglePause(this);
            });
        }
        
        dots.forEach(function(dot, index) {
            dot.addEventListener('click', function(e) {
                e.preventDefault();
                currentSlide = index;
                showSlide(currentSlide);
                updateDots();
                resetCarousel();
            });
        });
        
        const carousel = document.getElementById('heroCarousel');
        if (carousel) {
            carousel.addEventListener('mouseenter', function() {
                if (!isCarouselPaused) clearInterval(carouselInterval);
            });
            
            carousel.addEventListener('mouseleave', function() {
                if (!isCarouselPaused) startCarousel();
            });
            
            let touchStartX = 0;
            carousel.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            });
            
            carousel.addEventListener('touchend', function(e) {
                const touchEndX = e.changedTouches[0].screenX;
                if (touchEndX < touchStartX - 50) { changeSlide(1); resetCarousel(); }
                if (touchEndX > touchStartX + 50) { changeSlide(-1); resetCarousel(); }
            });
        }
    }

    function showSlide(index) {
        const slides = document.querySelectorAll('.carousel-slide');
        if (!slides || slides.length === 0) return;
        
        slides.forEach(function(slide) {
            slide.classList.remove('active');
            slide.style.display = 'none';
            slide.style.opacity = '0';
        });
        
        const currentSlideElement = slides[index];
        if (currentSlideElement) {
            currentSlideElement.style.display = 'block';
            setTimeout(function() {
                currentSlideElement.classList.add('active');
                currentSlideElement.style.opacity = '1';
            }, 50);
        }
        
        updateDots();
    }

    function updateDots() {
        const dots = document.querySelectorAll('.carousel-dot');
        dots.forEach(function(dot, index) {
            if (index === currentSlide) {
                dot.classList.add('active');
                dot.style.width = '32px';
                dot.style.backgroundColor = 'white';
            } else {
                dot.classList.remove('active');
                dot.style.width = '12px';
                dot.style.backgroundColor = 'rgba(255, 255, 255, 0.5)';
            }
        });
    }

    function changeSlide(direction) {
        const slides = document.querySelectorAll('.carousel-slide');
        currentSlide += direction;
        if (currentSlide >= slides.length) currentSlide = 0;
        else if (currentSlide < 0) currentSlide = slides.length - 1;
        showSlide(currentSlide);
    }

    function startCarousel() {
        clearInterval(carouselInterval);
        carouselInterval = setInterval(function() { changeSlide(1); }, 5000);
    }

    function resetCarousel() {
        if (!isCarouselPaused) {
            clearInterval(carouselInterval);
            startCarousel();
        }
    }

    function togglePause(button) {
        const icon = button.querySelector('i');
        if (isCarouselPaused) {
            isCarouselPaused = false;
            startCarousel();
            icon.classList.remove('fa-play');
            icon.classList.add('fa-pause');
        } else {
            isCarouselPaused = true;
            clearInterval(carouselInterval);
            icon.classList.remove('fa-pause');
            icon.classList.add('fa-play');
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCarousel);
    } else {
        initCarousel();
    }
})();

// ============================================
// TODAY'S DEALS HORIZONTAL SCROLL
// ============================================
(function() {
    const dealsContainer = document.getElementById('dealsContainer');
    const scrollLeftBtn = document.getElementById('dealsScrollLeft');
    const scrollRightBtn = document.getElementById('dealsScrollRight');
    
    if (dealsContainer && scrollLeftBtn && scrollRightBtn) {
        scrollLeftBtn.addEventListener('click', function() {
            dealsContainer.scrollBy({
                left: -400,
                behavior: 'smooth'
            });
        });
        
        scrollRightBtn.addEventListener('click', function() {
            dealsContainer.scrollBy({
                left: 400,
                behavior: 'smooth'
            });
        });
        
        // Show/hide scroll buttons based on scroll position
        function updateScrollButtons() {
            const maxScroll = dealsContainer.scrollWidth - dealsContainer.clientWidth;
            
            if (dealsContainer.scrollLeft <= 0) {
                scrollLeftBtn.style.opacity = '0.5';
                scrollLeftBtn.style.cursor = 'not-allowed';
            } else {
                scrollLeftBtn.style.opacity = '1';
                scrollLeftBtn.style.cursor = 'pointer';
            }
            
            if (dealsContainer.scrollLeft >= maxScroll - 10) {
                scrollRightBtn.style.opacity = '0.5';
                scrollRightBtn.style.cursor = 'not-allowed';
            } else {
                scrollRightBtn.style.opacity = '1';
                scrollRightBtn.style.cursor = 'pointer';
            }
        }
        
        dealsContainer.addEventListener('scroll', updateScrollButtons);
        updateScrollButtons();
    }
})();

// ============================================
// COUNTDOWN TIMER
// ============================================
(function() {
    function startCountdown() {
        const mainCountdown = document.getElementById('mainCountdown');
        if (!mainCountdown) return;
        
        let timeString = mainCountdown.textContent.trim();
        let parts = timeString.split(':');
        let totalSeconds = (parseInt(parts[0]) * 3600) + (parseInt(parts[1]) * 60) + parseInt(parts[2]);
        
        setInterval(function() {
            if (totalSeconds <= 0) {
                totalSeconds = 15 * 3600 + 49 * 60 + 30; // Reset to 15:49:30
            }
            
            totalSeconds--;
            
            let hours = Math.floor(totalSeconds / 3600);
            let minutes = Math.floor((totalSeconds % 3600) / 60);
            let seconds = totalSeconds % 60;
            
            let display = 
                String(hours).padStart(2, '0') + ':' + 
                String(minutes).padStart(2, '0') + ':' + 
                String(seconds).padStart(2, '0');
            
            mainCountdown.textContent = display;
            
            // Update all countdown timers in deal cards
            document.querySelectorAll('.countdown-timer').forEach(function(timer) {
                timer.textContent = display;
            });
        }, 1000);
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startCountdown);
    } else {
        startCountdown();
    }
})();
</script>

<?php include 'includes/footer.php'; ?>