<!-- Footer Component - footer.php -->
<style>
    .gold-text { color: #D4AF37; }
    .gold-border { border-color: #D4AF37; }
    .gold-bg { background-color: #D4AF37; }
    .gold-hover:hover { background-color: #C5A028; }
    .footer-link:hover { color: #D4AF37; }
</style>

<footer class="bg-gray-50 border-t-2 gold-border mt-16">
    <div class="container mx-auto px-4 py-8">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 mb-8">
            
            <!-- Shop Column -->
            <div>
                <h3 class="font-bold text-gray-800 mb-3 text-sm md:text-base">Shop</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="categories.php" class="text-gray-600 footer-link transition-colors">All Categories</a></li>
                    <li><a href="gifts.php" class="text-gray-600 footer-link transition-colors">Gift Ideas</a></li>
                    <li><a href="deals.php" class="text-gray-600 footer-link transition-colors">Today's Deals</a></li>
                    <li><a href="new-arrivals.php" class="text-gray-600 footer-link transition-colors">New Arrivals</a></li>
                    <li><a href="bestsellers.php" class="text-gray-600 footer-link transition-colors">Best Sellers</a></li>
                </ul>
            </div>

            <!-- Sell Column -->
            <div>
                <h3 class="font-bold text-gray-800 mb-3 text-sm md:text-base">Sell</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="start-selling.php" class="text-gray-600 footer-link transition-colors">Start Selling</a></li>
                    <li><a href="seller-guide.php" class="text-gray-600 footer-link transition-colors">Seller Guide</a></li>
                    <li><a href="seller-center.php" class="text-gray-600 footer-link transition-colors">Seller Center</a></li>
                    <li><a href="seller-fees.php" class="text-gray-600 footer-link transition-colors">Fees & Pricing</a></li>
                </ul>
            </div>

            <!-- Help & Support Column -->
            <div>
                <h3 class="font-bold text-gray-800 mb-3 text-sm md:text-base">Help & Support</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="help.php" class="text-gray-600 footer-link transition-colors">Help Center</a></li>
                    <li><a href="contact.php" class="text-gray-600 footer-link transition-colors">Contact Us</a></li>
                    <li><a href="shipping.php" class="text-gray-600 footer-link transition-colors">Shipping Info</a></li>
                    <li><a href="returns.php" class="text-gray-600 footer-link transition-colors">Returns & Refunds</a></li>
                    <li><a href="track-order.php" class="text-gray-600 footer-link transition-colors">Track Order</a></li>
                </ul>
            </div>

            <!-- About Column -->
            <div>
                <h3 class="font-bold text-gray-800 mb-3 text-sm md:text-base">About PrimeStore</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="about.php" class="text-gray-600 footer-link transition-colors">About Us</a></li>
                    <li><a href="careers.php" class="text-gray-600 footer-link transition-colors">Careers</a></li>
                    <li><a href="press.php" class="text-gray-600 footer-link transition-colors">Press & News</a></li>
                    <li><a href="investors.php" class="text-gray-600 footer-link transition-colors">Investors</a></li>
                </ul>
            </div>

            <!-- Community & Social Column -->
            <div>
                <h3 class="font-bold text-gray-800 mb-3 text-sm md:text-base">Community</h3>
                <ul class="space-y-2 text-sm mb-4">
                    <li><a href="blog.php" class="text-gray-600 footer-link transition-colors">Blog</a></li>
                    <li><a href="forum.php" class="text-gray-600 footer-link transition-colors">Community Forum</a></li>
                    <li><a href="events.php" class="text-gray-600 footer-link transition-colors">Events</a></li>
                </ul>
                
                <!-- Social Media Links -->
                <div class="flex space-x-3 mt-4">
                    <a href="#" class="text-gray-600 hover:gold-text transition-colors">
                        <i class="fab fa-facebook text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:gold-text transition-colors">
                        <i class="fab fa-twitter text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:gold-text transition-colors">
                        <i class="fab fa-instagram text-xl"></i>
                    </a>
                    <a href="#" class="text-gray-600 hover:gold-text transition-colors">
                        <i class="fab fa-youtube text-xl"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Accordion Footer (Hidden on larger screens) -->
        <div class="lg:hidden border-t border-gray-200 pt-6 mb-6">
            <button class="w-full flex justify-between items-center py-3 text-gray-800 font-semibold" onclick="toggleFooterSection('quickLinks')">
                <span>Quick Links</span>
                <i class="fas fa-chevron-down transition-transform" id="quickLinksIcon"></i>
            </button>
            <div id="quickLinks" class="hidden grid grid-cols-2 gap-4 py-3">
                <div>
                    <a href="my-account.php" class="block text-sm text-gray-600 footer-link py-1">My Account</a>
                    <a href="orders.php" class="block text-sm text-gray-600 footer-link py-1">Order History</a>
                    <a href="wishlist.php" class="block text-sm text-gray-600 footer-link py-1">Wishlist</a>
                </div>
                <div>
                    <a href="gift-cards.php" class="block text-sm text-gray-600 footer-link py-1">Gift Cards</a>
                    <a href="affiliate.php" class="block text-sm text-gray-600 footer-link py-1">Affiliate Program</a>
                    <a href="newsletter.php" class="block text-sm text-gray-600 footer-link py-1">Newsletter</a>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-200 pt-6 flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <!-- Copyright & Legal Links -->
            <div class="text-center md:text-left">
                <p class="text-xs text-gray-600 mb-2">
                    Copyright © 2024-2025 PrimeStore Inc. All Rights Reserved.
                </p>
                <div class="flex flex-wrap justify-center md:justify-start gap-x-4 gap-y-1 text-xs">
                    <a href="accessibility.php" class="text-gray-600 footer-link">Accessibility</a>
                    <a href="user-agreement.php" class="text-gray-600 footer-link">User Agreement</a>
                    <a href="privacy.php" class="text-gray-600 footer-link">Privacy Policy</a>
                    <a href="cookies.php" class="text-gray-600 footer-link">Cookies</a>
                    <a href="terms.php" class="text-gray-600 footer-link">Terms of Use</a>
                </div>
            </div>

            <!-- Country/Region Selector -->
            <div class="flex items-center space-x-2">
                <button class="flex items-center space-x-2 px-4 py-2 border-2 gold-border rounded-lg hover:bg-yellow-50 transition-colors text-sm">
                    <i class="fas fa-globe"></i>
                    <span class="font-medium">United States</span>
                    <i class="fas fa-chevron-down text-xs"></i>
                </button>
            </div>
        </div>

        <!-- Back to Top Button -->
        <div class="text-center mt-6">
            <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="gold-bg text-white px-6 py-2 rounded-full gold-hover transition-colors inline-flex items-center space-x-2 text-sm font-medium">
                <i class="fas fa-arrow-up"></i>
                <span>Back to Top</span>
            </button>
        </div>
    </div>
</footer>

<script>
function toggleFooterSection(sectionId) {
    const section = document.getElementById(sectionId);
    const icon = document.getElementById(sectionId + 'Icon');
    
    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        section.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>