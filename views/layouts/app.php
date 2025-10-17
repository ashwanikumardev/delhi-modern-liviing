<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Delhi Modern Living - Premium PG & Hostel Booking' ?></title>
    <meta name="description" content="<?= $description ?? 'Book premium PG and hostel accommodations in Delhi. Modern amenities, secure environment, and affordable pricing.' ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= asset('images/favicon.ico') ?>">
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/style.css') ?>">
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        secondary: {
                            500: '#6b7280',
                            600: '#4b5563',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
    <!-- Modern Navigation with Glassmorphism -->
    <nav class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-lg sticky top-0 z-50 transition-all duration-300 border-b border-gray-200/50 dark:border-gray-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo with Animation -->
                <div class="flex-shrink-0">
                    <a href="<?= url() ?>" class="flex items-center group">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-blue-600 rounded-lg blur opacity-25 group-hover:opacity-50 transition-opacity"></div>
                            <img class="relative h-10 w-auto" src="<?= asset('images/logo.png') ?>" alt="Delhi Modern Living" onerror="this.style.display='none'">
                        </div>
                        <span class="ml-3 text-xl font-bold bg-gradient-to-r from-primary-600 to-blue-600 bg-clip-text text-transparent group-hover:from-primary-700 group-hover:to-blue-700 transition-all">
                            Delhi Modern Living
                        </span>
                    </a>
                </div>

                <!-- Desktop Navigation with Modern Styling -->
                <div class="hidden md:block">
                    <div class="ml-10 flex items-center space-x-2">
                        <a href="<?= url() ?>" class="group relative text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 hover:bg-primary-50 dark:hover:bg-gray-700">
                            <span class="relative z-10">Home</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-blue-600 rounded-xl opacity-0 group-hover:opacity-10 transition-opacity"></div>
                        </a>
                        <a href="<?= url('rooms') ?>" class="group relative text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 hover:bg-primary-50 dark:hover:bg-gray-700">
                            <span class="relative z-10">Rooms</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-blue-600 rounded-xl opacity-0 group-hover:opacity-10 transition-opacity"></div>
                        </a>
                        <a href="<?= url('about') ?>" class="group relative text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 hover:bg-primary-50 dark:hover:bg-gray-700">
                            <span class="relative z-10">About</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-blue-600 rounded-xl opacity-0 group-hover:opacity-10 transition-opacity"></div>
                        </a>
                        <a href="<?= url('contact') ?>" class="group relative text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-4 py-2 rounded-xl text-sm font-semibold transition-all duration-300 hover:bg-primary-50 dark:hover:bg-gray-700">
                            <span class="relative z-10">Contact</span>
                            <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-blue-600 rounded-xl opacity-0 group-hover:opacity-10 transition-opacity"></div>
                        </a>
                    </div>
                </div>

                <!-- User Menu with Modern Design -->
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6 gap-3">
                        <!-- Dark Mode Toggle -->
                        <button id="theme-toggle" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300" title="Toggle dark mode">
                            <i class="fas fa-moon dark:hidden text-lg"></i>
                            <i class="fas fa-sun hidden dark:inline text-lg"></i>
                        </button>

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <!-- Cart with Badge -->
                            <a href="<?= url('cart') ?>" class="relative text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 group">
                                <i class="fas fa-shopping-cart text-lg group-hover:scale-110 transition-transform"></i>
                                <span id="cart-count" class="absolute -top-1 -right-1 bg-gradient-to-r from-red-500 to-pink-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden font-bold shadow-lg">0</span>
                            </a>

                            <!-- User Dropdown with Modern Design -->
                            <div class="ml-2 relative">
                                <div class="relative inline-block text-left">
                                    <button type="button" class="flex items-center gap-2 px-3 py-2 text-sm rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-300" id="user-menu-button">
                                        <span class="sr-only">Open user menu</span>
                                        <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-primary-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-lg">
                                            <?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?>
                                        </div>
                                        <span class="text-gray-700 dark:text-gray-300 font-semibold"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                    </button>

                                    <div class="origin-top-right absolute right-0 mt-3 w-56 rounded-2xl shadow-2xl bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 hidden overflow-hidden border border-gray-100 dark:border-gray-700" id="user-menu">
                                        <div class="py-2">
                                            <a href="<?= url('profile') ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-primary-50 hover:to-blue-50 dark:hover:from-gray-700 dark:hover:to-gray-700 transition-all duration-300 group">
                                                <i class="fas fa-user text-primary-600 group-hover:scale-110 transition-transform"></i>
                                                <span class="font-medium">Profile</span>
                                            </a>
                                            <a href="<?= url('orders') ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gradient-to-r hover:from-primary-50 hover:to-blue-50 dark:hover:from-gray-700 dark:hover:to-gray-700 transition-all duration-300 group">
                                                <i class="fas fa-calendar-check text-primary-600 group-hover:scale-110 transition-transform"></i>
                                                <span class="font-medium">My Bookings</span>
                                            </a>
                                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                                            <a href="<?= url('auth/logout') ?>" class="flex items-center gap-3 px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-300 group">
                                                <i class="fas fa-sign-out-alt group-hover:scale-110 transition-transform"></i>
                                                <span class="font-medium">Logout</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="<?= url('auth/login') ?>" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </a>
                            <a href="<?= url('auth/signup') ?>" class="bg-gradient-to-r from-primary-600 to-blue-600 text-white hover:from-primary-700 hover:to-blue-700 px-5 py-2.5 rounded-xl text-sm font-bold ml-2 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-user-plus mr-2"></i>Sign Up
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Modern Mobile menu button -->
                <div class="md:hidden flex items-center gap-2">
                    <!-- Dark Mode Toggle (Mobile) -->
                    <button id="theme-toggle-mobile" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300" title="Toggle dark mode">
                        <i class="fas fa-moon dark:hidden text-lg"></i>
                        <i class="fas fa-sun hidden dark:inline text-lg"></i>
                    </button>

                    <button type="button" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-all duration-300" id="mobile-menu-button">
                        <span class="sr-only">Open main menu</span>
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Modern Mobile Navigation with Slide Animation -->
        <div class="md:hidden hidden" id="mobile-menu">
            <div class="px-4 pt-4 pb-6 space-y-2 bg-white/95 dark:bg-gray-800/95 backdrop-blur-lg border-t border-gray-200 dark:border-gray-700">
                <a href="<?= url() ?>" class="group flex items-center gap-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-4 py-3 rounded-xl text-base font-semibold hover:bg-gradient-to-r hover:from-primary-50 hover:to-blue-50 dark:hover:from-gray-700 dark:hover:to-gray-700 transition-all duration-300">
                    <i class="fas fa-home text-primary-600 group-hover:scale-110 transition-transform"></i>
                    <span>Home</span>
                </a>
                <a href="<?= url('rooms') ?>" class="group flex items-center gap-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-4 py-3 rounded-xl text-base font-semibold hover:bg-gradient-to-r hover:from-primary-50 hover:to-blue-50 dark:hover:from-gray-700 dark:hover:to-gray-700 transition-all duration-300">
                    <i class="fas fa-bed text-primary-600 group-hover:scale-110 transition-transform"></i>
                    <span>Rooms</span>
                </a>
                <a href="<?= url('about') ?>" class="group flex items-center gap-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-4 py-3 rounded-xl text-base font-semibold hover:bg-gradient-to-r hover:from-primary-50 hover:to-blue-50 dark:hover:from-gray-700 dark:hover:to-gray-700 transition-all duration-300">
                    <i class="fas fa-info-circle text-primary-600 group-hover:scale-110 transition-transform"></i>
                    <span>About</span>
                </a>
                <a href="<?= url('contact') ?>" class="group flex items-center gap-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-4 py-3 rounded-xl text-base font-semibold hover:bg-gradient-to-r hover:from-primary-50 hover:to-blue-50 dark:hover:from-gray-700 dark:hover:to-gray-700 transition-all duration-300">
                    <i class="fas fa-envelope text-primary-600 group-hover:scale-110 transition-transform"></i>
                    <span>Contact</span>
                </a>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                        <div class="flex items-center gap-3 px-4 py-3 mb-2 bg-gradient-to-r from-primary-50 to-blue-50 dark:from-gray-700 dark:to-gray-700 rounded-xl">
                            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-primary-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-lg">
                                <?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?>
                            </div>
                            <span class="text-gray-900 dark:text-white font-bold"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                        </div>
                        <a href="<?= url('cart') ?>" class="group flex items-center gap-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-4 py-3 rounded-xl text-base font-semibold hover:bg-gradient-to-r hover:from-primary-50 hover:to-blue-50 dark:hover:from-gray-700 dark:hover:to-gray-700 transition-all duration-300">
                            <i class="fas fa-shopping-cart text-primary-600 group-hover:scale-110 transition-transform"></i>
                            <span>Cart</span>
                        </a>
                        <a href="<?= url('profile') ?>" class="group flex items-center gap-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-4 py-3 rounded-xl text-base font-semibold hover:bg-gradient-to-r hover:from-primary-50 hover:to-blue-50 dark:hover:from-gray-700 dark:hover:to-gray-700 transition-all duration-300">
                            <i class="fas fa-user text-primary-600 group-hover:scale-110 transition-transform"></i>
                            <span>Profile</span>
                        </a>
                        <a href="<?= url('orders') ?>" class="group flex items-center gap-3 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-4 py-3 rounded-xl text-base font-semibold hover:bg-gradient-to-r hover:from-primary-50 hover:to-blue-50 dark:hover:from-gray-700 dark:hover:to-gray-700 transition-all duration-300">
                            <i class="fas fa-calendar-check text-primary-600 group-hover:scale-110 transition-transform"></i>
                            <span>My Bookings</span>
                        </a>
                        <a href="<?= url('auth/logout') ?>" class="group flex items-center gap-3 text-red-600 dark:text-red-400 px-4 py-3 rounded-xl text-base font-semibold hover:bg-red-50 dark:hover:bg-red-900/20 transition-all duration-300">
                            <i class="fas fa-sign-out-alt group-hover:scale-110 transition-transform"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4 space-y-2">
                        <a href="<?= url('auth/login') ?>" class="group flex items-center justify-center gap-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 px-4 py-3 rounded-xl text-base font-bold hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                            <i class="fas fa-sign-in-alt group-hover:scale-110 transition-transform"></i>
                            <span>Login</span>
                        </a>
                        <a href="<?= url('auth/signup') ?>" class="flex items-center justify-center gap-2 bg-gradient-to-r from-primary-600 to-blue-600 text-white hover:from-primary-700 hover:to-blue-700 px-4 py-3 rounded-xl text-base font-bold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-user-plus"></i>
                            <span>Sign Up</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mx-4 mt-4" id="flash-message">
            <div class="flex justify-between items-center">
                <span><?= htmlspecialchars($_SESSION['success']) ?></span>
                <button onclick="this.parentElement.parentElement.remove()" class="text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mx-4 mt-4" id="flash-message">
            <div class="flex justify-between items-center">
                <span><?= htmlspecialchars($_SESSION['error']) ?></span>
                <button onclick="this.parentElement.parentElement.remove()" class="text-red-700 hover:text-red-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main>
        <?= $content ?? '' ?>
    </main>

    <!-- WhatsApp Floating Button -->
    <div class="fixed bottom-6 right-6 z-50">
        <a href="https://wa.me/917654353464?text=Hi! I'm interested in your PG/Hostel accommodations. Can you help me?" 
           target="_blank"
           class="bg-green-500 hover:bg-green-600 text-white w-14 h-14 rounded-full flex items-center justify-center shadow-lg hover:shadow-xl transition-all duration-300 animate-pulse">
            <i class="fab fa-whatsapp text-2xl"></i>
        </a>
    </div>
    
    <!-- Footer -->
    <footer class="bg-gray-800 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Company Info -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center mb-4">
                        <img class="h-8 w-auto" src="<?= asset('images/logo-white.png') ?>" alt="Delhi Modern Living" onerror="this.style.display='none'">
                        <span class="ml-2 text-xl font-bold">Delhi Modern Living</span>
                    </div>
                    <p class="text-gray-300 mb-4">Premium PG and hostel accommodations in Delhi with modern amenities and secure environment.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-300 hover:text-white"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://wa.me/917654353464" target="_blank" class="text-gray-300 hover:text-green-400"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="<?= url() ?>" class="text-gray-300 hover:text-white">Home</a></li>
                        <li><a href="<?= url('rooms') ?>" class="text-gray-300 hover:text-white">Rooms</a></li>
                        <li><a href="<?= url('about') ?>" class="text-gray-300 hover:text-white">About</a></li>
                        <li><a href="<?= url('contact') ?>" class="text-gray-300 hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Services</h3>
                    <ul class="space-y-2">
                        <li><a href="<?= url('rooms?category=single') ?>" class="text-gray-300 hover:text-white">Single Rooms</a></li>
                        <li><a href="<?= url('rooms?category=double') ?>" class="text-gray-300 hover:text-white">Shared Rooms</a></li>
                        <li><a href="<?= url('rooms?category=pg') ?>" class="text-gray-300 hover:text-white">PG Accommodation</a></li>
                        <li><a href="<?= url('rooms?category=male') ?>" class="text-gray-300 hover:text-white">Male PG</a></li>
                        <li><a href="<?= url('rooms?category=female') ?>" class="text-gray-300 hover:text-white">Female PG</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Info</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><i class="fas fa-phone mr-2"></i> <a href="tel:+917654353464" class="hover:text-white">+91-7654353464</a></li>
                        <li><i class="fas fa-envelope mr-2"></i> <a href="mailto:info@delhimodernliving.com" class="hover:text-white">info@delhimodernliving.com</a></li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Connaught Place, New Delhi</li>
                    </ul>
                    
                    <!-- Mini Google Map -->
                    <div class="mt-4">
                        <h4 class="text-sm font-semibold mb-2">Find Us</h4>
                        <div class="h-24 rounded overflow-hidden">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3501.674862842267!2d77.21787831508236!3d28.63124998240764!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390cfd0683d1e6d1%3A0x8e3f4b2b7c5a8b9c!2sConnaught%20Place%2C%20New%20Delhi%2C%20Delhi!5e0!3m2!1sen!2sin!4v1634567890123!5m2!1sen!2sin"
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <a href="https://maps.google.com/?q=Connaught+Place+New+Delhi" 
                           target="_blank"
                           class="text-xs text-primary-400 hover:text-primary-300 mt-1 inline-block">
                            <i class="fas fa-directions mr-1"></i>Get Directions
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-700 mt-8 pt-8 text-center">
                <p class="text-gray-300">
                    &copy; <?= date('Y') ?> Delhi Modern Living. All rights reserved.
                </p>
            </div>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        window.BASE_PATH = '<?= url('') ?>';
        window.API_BASE = '<?= url('api') ?>';
    </script>
    <script src="<?= asset('js/app.js') ?>"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });
        
        // User menu toggle
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');
        
        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', function() {
                userMenu.classList.toggle('hidden');
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                }
            });
        }
        
        // Auto-hide flash messages
        setTimeout(function() {
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                flashMessage.style.display = 'none';
            }
        }, 5000);
        
        // Dark Mode Toggle
        const themeToggle = document.getElementById('theme-toggle');
        const themeToggleMobile = document.getElementById('theme-toggle-mobile');
        const html = document.documentElement;
        
        // Check for saved theme preference or default to light mode
        const currentTheme = localStorage.getItem('theme') || 'light';
        if (currentTheme === 'dark') {
            html.classList.add('dark');
        }
        
        function toggleTheme() {
            html.classList.toggle('dark');
            const theme = html.classList.contains('dark') ? 'dark' : 'light';
            localStorage.setItem('theme', theme);
        }
        
        if (themeToggle) {
            themeToggle.addEventListener('click', toggleTheme);
        }
        
        if (themeToggleMobile) {
            themeToggleMobile.addEventListener('click', toggleTheme);
        }
    </script>
</body>
</html>
