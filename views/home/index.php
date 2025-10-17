<?php
$title = 'Delhi Modern Living - Premium PG & Hostel Booking';
$description = 'Book premium PG and hostel accommodations in Delhi. Modern amenities, secure environment, and affordable pricing.';

ob_start();
?>

<!-- Hero Section with Modern Design -->
<section class="relative min-h-[600px] bg-gradient-to-br from-blue-600 via-primary-600 to-purple-600 text-white overflow-hidden">
    <!-- Animated Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-blob"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-yellow-300 rounded-full mix-blend-overlay filter blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-pink-300 rounded-full mix-blend-overlay filter blur-3xl animate-blob animation-delay-4000"></div>
    </div>

    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-gradient-to-b from-transparent via-black/20 to-black/40"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32">
        <div class="text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md border border-white/20 rounded-full px-4 py-2 mb-6 animate-fade-in-down">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span class="text-sm font-medium">Premium Accommodations Available</span>
            </div>

            <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold mb-6 animate-fade-in-up">
                Find Your Perfect
                <span class="block mt-2 bg-gradient-to-r from-yellow-300 via-yellow-400 to-orange-400 bg-clip-text text-transparent">
                    Home Away From Home
                </span>
            </h1>

            <p class="text-lg md:text-xl lg:text-2xl mb-10 max-w-3xl mx-auto text-gray-100 leading-relaxed animate-fade-in-up animation-delay-200">
                Premium PG and hostel accommodations in Delhi with modern amenities,
                secure environment, and affordable pricing.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fade-in-up animation-delay-400">
                <a href="<?= url('rooms') ?>" class="group relative bg-gradient-to-r from-yellow-400 to-orange-400 text-gray-900 hover:from-yellow-300 hover:to-orange-300 px-8 py-4 rounded-xl text-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-2xl shadow-lg">
                    <span class="relative z-10 flex items-center gap-2">
                        <i class="fas fa-search"></i>
                        Explore Rooms
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                    </span>
                </a>
                <a href="#featured" class="group backdrop-blur-md bg-white/10 border-2 border-white/30 text-white hover:bg-white hover:text-primary-600 px-8 py-4 rounded-xl text-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-2xl">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-star"></i>
                        View Featured
                    </span>
                </a>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-6 max-w-2xl mx-auto mt-16 animate-fade-in-up animation-delay-600">
                <div class="backdrop-blur-md bg-white/10 border border-white/20 rounded-2xl p-4">
                    <div class="text-3xl font-bold text-yellow-300">500+</div>
                    <div class="text-sm text-gray-200 mt-1">Happy Residents</div>
                </div>
                <div class="backdrop-blur-md bg-white/10 border border-white/20 rounded-2xl p-4">
                    <div class="text-3xl font-bold text-yellow-300">50+</div>
                    <div class="text-sm text-gray-200 mt-1">Premium Rooms</div>
                </div>
                <div class="backdrop-blur-md bg-white/10 border border-white/20 rounded-2xl p-4">
                    <div class="text-3xl font-bold text-yellow-300">4.8★</div>
                    <div class="text-sm text-gray-200 mt-1">Average Rating</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Wave Divider -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
            <path d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z" fill="white"/>
        </svg>
    </div>
</section>

<style>
@keyframes blob {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -50px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.9); }
}
@keyframes fade-in-down {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fade-in-up {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-blob { animation: blob 7s infinite; }
.animation-delay-2000 { animation-delay: 2s; }
.animation-delay-4000 { animation-delay: 4s; }
.animate-fade-in-down { animation: fade-in-down 0.6s ease-out; }
.animate-fade-in-up { animation: fade-in-up 0.6s ease-out; }
.animation-delay-200 { animation-delay: 0.2s; animation-fill-mode: both; }
.animation-delay-400 { animation-delay: 0.4s; animation-fill-mode: both; }
.animation-delay-600 { animation-delay: 0.6s; animation-fill-mode: both; }
</style>

<!-- Modern Search Section -->
<section class="bg-white py-12 -mt-16 relative z-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 p-6 md:p-8">
            <form action="<?= url('rooms') ?>" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Search Input -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 group-focus-within:text-primary-600 transition-colors"></i>
                        </div>
                        <input type="text" name="search" placeholder="Search by location, PG name..."
                               class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-gray-300">
                    </div>

                    <!-- Category Select -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-th-large text-gray-400 group-focus-within:text-primary-600 transition-colors"></i>
                        </div>
                        <select name="category" class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-gray-300 appearance-none bg-white cursor-pointer">
                            <option value="">All Categories</option>
                            <option value="single">Single Room</option>
                            <option value="double">Double Room</option>
                            <option value="male">Male PG</option>
                            <option value="female">Female PG</option>
                            <option value="pg">General PG</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Sort Select -->
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-sort text-gray-400 group-focus-within:text-primary-600 transition-colors"></i>
                        </div>
                        <select name="sort" class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-300 hover:border-gray-300 appearance-none bg-white cursor-pointer">
                            <option value="newest">Newest First</option>
                            <option value="price_low">Price: Low to High</option>
                            <option value="price_high">Price: High to Low</option>
                            <option value="popular">Most Popular</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                            <i class="fas fa-chevron-down text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Search Button -->
                <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-primary-600 to-blue-600 text-white hover:from-primary-700 hover:to-blue-700 px-10 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-xl shadow-lg flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i>
                    <span>Search Rooms</span>
                    <i class="fas fa-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Modern Categories Section -->
<section class="py-20 bg-gradient-to-b from-gray-50 to-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block bg-primary-100 text-primary-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                <i class="fas fa-th-large mr-2"></i>Explore Categories
            </div>
            <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4">
                Room <span class="bg-gradient-to-r from-primary-600 to-blue-600 bg-clip-text text-transparent">Categories</span>
            </h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                Choose from our wide range of accommodation options designed for your comfort and convenience.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            <?php
            $categoryIcons = [
                'single' => 'fas fa-bed',
                'double' => 'fas fa-bed',
                'male' => 'fas fa-male',
                'female' => 'fas fa-female',
                'pg' => 'fas fa-home'
            ];

            $categoryNames = [
                'single' => 'Single Rooms',
                'double' => 'Double Rooms',
                'male' => 'Male PG',
                'female' => 'Female PG',
                'pg' => 'General PG'
            ];

            $categoryColors = [
                'single' => 'from-blue-500 to-blue-600',
                'double' => 'from-purple-500 to-purple-600',
                'male' => 'from-green-500 to-green-600',
                'female' => 'from-pink-500 to-pink-600',
                'pg' => 'from-orange-500 to-orange-600'
            ];

            foreach ($categories as $index => $category):
                $colorClass = $categoryColors[$category['category']] ?? 'from-primary-500 to-primary-600';
            ?>
                <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-2">
                    <!-- Gradient Background -->
                    <div class="absolute inset-0 bg-gradient-to-br <?= $colorClass ?> opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>

                    <!-- Content -->
                    <div class="relative p-8 text-center">
                        <!-- Icon -->
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br <?= $colorClass ?> rounded-2xl mb-6 transform group-hover:scale-110 group-hover:rotate-6 transition-all duration-500 shadow-lg">
                            <i class="<?= $categoryIcons[$category['category']] ?? 'fas fa-home' ?> text-3xl text-white"></i>
                        </div>

                        <h3 class="text-2xl font-bold text-gray-900 mb-3">
                            <?= $categoryNames[$category['category']] ?? ucfirst($category['category']) ?>
                        </h3>

                        <div class="flex items-center justify-center gap-2 text-gray-600 mb-4">
                            <i class="fas fa-home text-sm"></i>
                            <span class="text-sm font-medium"><?= $category['count'] ?> properties available</span>
                        </div>

                        <div class="bg-gradient-to-r <?= $colorClass ?> bg-clip-text text-transparent">
                            <p class="text-2xl font-bold mb-6">
                                ₹<?= number_format($category['min_price']) ?>
                                <span class="text-sm text-gray-500">/month</span>
                            </p>
                        </div>

                        <a href="<?= url('rooms?category=' . $category['category']) ?>"
                           class="inline-flex items-center gap-2 bg-gradient-to-r <?= $colorClass ?> text-white hover:shadow-xl px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform group-hover:scale-105">
                            <span>View Rooms</span>
                            <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                    <!-- Decorative Element -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br <?= $colorClass ?> opacity-5 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Modern Featured Rooms Section -->
<section id="featured" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="inline-block bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                <i class="fas fa-star mr-2"></i>Premium Selection
            </div>
            <h2 class="text-3xl md:text-5xl font-bold text-gray-900 mb-4">
                Featured <span class="bg-gradient-to-r from-yellow-500 to-orange-500 bg-clip-text text-transparent">Rooms</span>
            </h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
                Discover our handpicked premium accommodations with the best amenities and locations.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            require_once 'helpers/ImageHelper.php';
            require_once 'helpers/AmenityHelper.php';
            foreach ($featured_rooms as $room):
                $images = json_decode($room['images'] ?? '[]', true);
                $amenities = json_decode($room['amenities'] ?? '[]', true);
                // Use default amenities if none exist
                if (empty($amenities)) {
                    $amenities = ['AC', 'WiFi', 'Furnished', 'Meals', 'Laundry', 'Security'];
                }
            ?>
                <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden transform hover:-translate-y-2">
                    <!-- Image Container -->
                    <div class="relative overflow-hidden">
                        <img src="<?= ImageHelper::getImageUrl($images[0] ?? null) ?>"
                             alt="<?= htmlspecialchars($room['title']) ?>"
                             class="w-full h-64 object-cover transform group-hover:scale-110 transition-transform duration-700"
                             onerror="this.src='<?= url('assets/images/placeholder-room.jpg') ?>'">

                        <!-- Gradient Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                        <!-- Badges -->
                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                            <span class="backdrop-blur-md bg-white/90 text-primary-700 px-3 py-1.5 rounded-lg text-sm font-bold shadow-lg">
                                <i class="fas fa-tag mr-1"></i><?= ucfirst($room['category']) ?>
                            </span>
                        </div>
                        <div class="absolute top-4 right-4">
                            <span class="backdrop-blur-md bg-green-500/90 text-white px-3 py-1.5 rounded-lg text-sm font-bold shadow-lg flex items-center gap-1">
                                <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                <?= ucfirst($room['availability_status']) ?>
                            </span>
                        </div>

                        <!-- Wishlist Button -->
                        <button class="absolute bottom-4 right-4 w-12 h-12 bg-white/90 backdrop-blur-md rounded-full flex items-center justify-center text-gray-600 hover:text-red-500 hover:bg-white transition-all duration-300 transform hover:scale-110 shadow-lg opacity-0 group-hover:opacity-100">
                            <i class="fas fa-heart"></i>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-primary-600 transition-colors line-clamp-1">
                            <?= htmlspecialchars($room['title']) ?>
                        </h3>

                        <?= AmenityHelper::renderLocation($room['address'], $room['city'], $room['pincode']) ?>

                        <!-- Amenities -->
                        <div class="flex flex-wrap gap-2 mb-5">
                            <?php foreach (array_slice($amenities, 0, 3) as $amenity): ?>
                                <span class="bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-medium border border-gray-200">
                                    <i class="<?= AmenityHelper::getAmenityIcon($amenity) ?> <?= AmenityHelper::getAmenityColor($amenity) ?> mr-1"></i><?= htmlspecialchars($amenity) ?>
                                </span>
                            <?php endforeach; ?>
                            <?php if (count($amenities) > 3): ?>
                                <span class="bg-primary-50 text-primary-700 px-3 py-1.5 rounded-lg text-xs font-bold border border-primary-200">
                                    +<?= count($amenities) - 3 ?> more
                                </span>
                            <?php endif; ?>
                        </div>

                        <!-- Pricing -->
                        <div class="flex justify-between items-center mb-5 pb-5 border-b border-gray-100">
                            <div>
                                <div class="text-3xl font-bold bg-gradient-to-r from-primary-600 to-blue-600 bg-clip-text text-transparent">
                                    ₹<?= number_format($room['price_per_month']) ?>
                                </div>
                                <span class="text-sm text-gray-500 font-medium">/month</span>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-gray-500 mb-1">Deposit</div>
                                <div class="text-sm font-bold text-gray-700">₹<?= number_format($room['deposit']) ?></div>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <a href="<?= url('rooms/' . $room['id']) ?>"
                           class="group/btn w-full bg-gradient-to-r from-primary-600 to-blue-600 text-white hover:from-primary-700 hover:to-blue-700 py-3.5 px-4 rounded-xl text-center font-bold transition-all duration-300 transform hover:scale-105 hover:shadow-xl shadow-lg flex items-center justify-center gap-2">
                            <span>View Details</span>
                            <i class="fas fa-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- View All Button -->
        <div class="text-center mt-16">
            <a href="<?= url('rooms') ?>" class="inline-flex items-center gap-3 bg-gradient-to-r from-primary-600 to-blue-600 text-white hover:from-primary-700 hover:to-blue-700 px-10 py-4 rounded-xl text-lg font-bold transition-all duration-300 transform hover:scale-105 hover:shadow-2xl shadow-lg">
                <i class="fas fa-th-large"></i>
                <span>View All Rooms</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">What Our Guests Say</h2>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Read reviews from our satisfied guests who have experienced our premium accommodations.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex items-center mb-4">
                        <img src="<?= htmlspecialchars($testimonial['image']) ?>" 
                             alt="<?= htmlspecialchars($testimonial['name']) ?>"
                             class="w-12 h-12 rounded-full object-cover mr-4"
                             onerror="this.src='<?= asset('images/default-avatar.jpg') ?>'">
                        <div>
                            <h4 class="font-semibold text-gray-900"><?= htmlspecialchars($testimonial['name']) ?></h4>
                            <div class="flex text-yellow-400">
                                <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                                    <i class="fas fa-star"></i>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    <p class="text-gray-600 italic">
                        "<?= htmlspecialchars($testimonial['text']) ?>"
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h2>
            <p class="text-lg text-gray-600">
                Find answers to common questions about our accommodations and booking process.
            </p>
        </div>
        
        <div class="space-y-4">
            <?php foreach ($faqs as $index => $faq): ?>
                <div class="border border-gray-200 rounded-lg">
                    <button class="w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-primary-500 faq-toggle" 
                            data-target="faq-<?= $index ?>">
                        <div class="flex justify-between items-center">
                            <h3 class="text-lg font-semibold text-gray-900">
                                <?= htmlspecialchars($faq['question']) ?>
                            </h3>
                            <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200"></i>
                        </div>
                    </button>
                    <div id="faq-<?= $index ?>" class="hidden px-6 pb-4">
                        <p class="text-gray-600">
                            <?= htmlspecialchars($faq['answer']) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-primary-600 text-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Ready to Book Your Stay?</h2>
        <p class="text-xl mb-8">
            Join thousands of satisfied guests who have found their perfect accommodation with us.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?= url('rooms') ?>" class="bg-white text-primary-600 hover:bg-gray-100 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                Browse Rooms
            </a>
            <a href="<?= url('auth/signup') ?>" class="border-2 border-white text-white hover:bg-white hover:text-primary-600 px-8 py-3 rounded-lg text-lg font-semibold transition duration-300">
                Create Account
            </a>
        </div>
    </div>
</section>

<script>
// FAQ Toggle Functionality
document.querySelectorAll('.faq-toggle').forEach(button => {
    button.addEventListener('click', () => {
        const target = document.getElementById(button.dataset.target);
        const icon = button.querySelector('i');
        
        if (target.classList.contains('hidden')) {
            target.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            target.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/app.php';
?>
