<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard - Delhi Modern Living' ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= asset('images/favicon.ico') ?>">
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
    
    <!-- Tailwind Config -->
    <script>
        tailwind.config = {
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
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar - Hidden on mobile, shown on desktop -->
        <div class="hidden lg:flex bg-gray-800 text-white w-64 min-h-screen flex-col">
            <!-- Logo -->
            <div class="flex items-center justify-center h-16 bg-gray-900">
                <div class="flex items-center">
                    <img class="h-8 w-auto" src="<?= asset('images/logo-white.png') ?>" alt="Delhi Modern Living" onerror="this.style.display='none'">
                    <span class="ml-2 text-lg font-bold">Admin Panel</span>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 admin-sidebar overflow-y-auto">
                <a href="<?= url('admin') ?>" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors <?= strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/admin') === 0 && strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/admin/') === false ? 'bg-gray-700' : '' ?>">
                    <i class="fas fa-tachometer-alt mr-3 w-5"></i>
                    <span>Dashboard</span>
                </a>

                <a href="<?= url('admin/users') ?>" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors <?= strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/admin/users') === 0 ? 'bg-gray-700' : '' ?>">
                    <i class="fas fa-users mr-3 w-5"></i>
                    <span>Users</span>
                </a>

                <a href="<?= url('admin/rooms') ?>" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors <?= strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/admin/rooms') === 0 ? 'bg-gray-700' : '' ?>">
                    <i class="fas fa-bed mr-3 w-5"></i>
                    <span>Rooms</span>
                </a>

                <a href="<?= url('admin/bookings') ?>" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors <?= strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/admin/bookings') === 0 ? 'bg-gray-700' : '' ?>">
                    <i class="fas fa-calendar-check mr-3 w-5"></i>
                    <span>Bookings</span>
                </a>

                <a href="<?= url('admin/orders') ?>" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors <?= strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/admin/orders') === 0 ? 'bg-gray-700' : '' ?>">
                    <i class="fas fa-shopping-cart mr-3 w-5"></i>
                    <span>Orders</span>
                </a>

                <a href="<?= url('admin/tickets') ?>" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors <?= strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/admin/tickets') === 0 ? 'bg-gray-700' : '' ?>">
                    <i class="fas fa-ticket-alt mr-3 w-5"></i>
                    <span>Support Tickets</span>
                </a>

                <a href="<?= url('admin/reports') ?>" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors <?= strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/admin/reports') === 0 ? 'bg-gray-700' : '' ?>">
                    <i class="fas fa-chart-bar mr-3 w-5"></i>
                    <span>Reports</span>
                </a>

                <a href="<?= url('admin/settings') ?>" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors <?= strpos(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/admin/settings') === 0 ? 'bg-gray-700' : '' ?>">
                    <i class="fas fa-cog mr-3 w-5"></i>
                    <span>Settings</span>
                </a>

                <div class="border-t border-gray-700 my-4"></div>

                <a href="<?= url('') ?>" target="_blank" class="flex items-center px-4 py-2 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-external-link-alt mr-3 w-5"></i>
                    <span>View Website</span>
                </a>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
                <div class="flex items-center justify-between px-4 lg:px-6 py-4">
                    <div class="flex items-center">
                        <button type="button" class="text-gray-500 hover:text-gray-700 lg:hidden mr-3" id="mobile-menu-button">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h1 class="text-lg lg:text-xl font-semibold text-gray-900 truncate">
                            <?= $page_title ?? 'Dashboard' ?>
                        </h1>
                    </div>
                    
                    <div class="flex items-center space-x-2 lg:space-x-4">
                        <!-- Notifications -->
                        <div class="relative hidden sm:block">
                            <button type="button" class="text-gray-500 hover:text-gray-700 p-2 rounded-full transition-colors">
                                <i class="fas fa-bell text-lg lg:text-xl"></i>
                                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400"></span>
                            </button>
                        </div>

                        <!-- Admin Profile Dropdown -->
                        <div class="relative">
                            <button type="button" class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all" id="admin-menu-button">
                                <div class="h-8 w-8 rounded-full bg-primary-500 flex items-center justify-center text-white text-sm font-medium">
                                    <?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)) ?>
                                </div>
                                <span class="ml-2 text-gray-700 hidden md:block max-w-32 truncate"><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></span>
                                <i class="fas fa-chevron-down ml-1 text-gray-400 hidden md:block text-xs"></i>
                            </button>

                            <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50" id="admin-menu">
                                <div class="py-1">
                                    <div class="px-4 py-2 text-sm text-gray-500 border-b">
                                        <div class="truncate"><?= htmlspecialchars($_SESSION['admin_email'] ?? 'admin@example.com') ?></div>
                                    </div>
                                    <a href="<?= url('admin/profile') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-user mr-2"></i>Profile Settings
                                    </a>
                                    <a href="<?= url('admin/logout') ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Flash Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 mx-4 lg:mx-6 mt-4 rounded" id="flash-message">
                    <div class="flex justify-between items-center">
                        <span class="text-sm lg:text-base"><?= htmlspecialchars($_SESSION['success']) ?></span>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-green-700 hover:text-green-900 ml-2">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 mx-4 lg:mx-6 mt-4 rounded" id="flash-message">
                    <div class="flex justify-between items-center">
                        <span class="text-sm lg:text-base"><?= htmlspecialchars($_SESSION['error']) ?></span>
                        <button onclick="this.parentElement.parentElement.remove()" class="text-red-700 hover:text-red-900 ml-2">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100">
                <div class="container mx-auto px-4 lg:px-6 py-4 lg:py-8">
                    <?= $content ?? '' ?>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Mobile Sidebar Overlay -->
    <div class="fixed inset-0 z-50 lg:hidden hidden" id="mobile-sidebar-overlay">
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" onclick="toggleMobileSidebar()"></div>
        <div class="fixed inset-y-0 left-0 w-64 bg-gray-800 text-white shadow-xl transform transition-transform">
            <!-- Mobile sidebar header -->
            <div class="flex items-center justify-between h-16 bg-gray-900 px-4">
                <div class="flex items-center">
                    <img class="h-8 w-auto" src="<?= asset('images/logo-white.png') ?>" alt="Delhi Modern Living" onerror="this.style.display='none'">
                    <span class="ml-2 text-lg font-bold">Admin Panel</span>
                </div>
                <button onclick="toggleMobileSidebar()" class="text-gray-300 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Mobile navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="<?= url('admin') ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors" onclick="toggleMobileSidebar()">
                    <i class="fas fa-tachometer-alt mr-3 w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?= url('admin/users') ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors" onclick="toggleMobileSidebar()">
                    <i class="fas fa-users mr-3 w-5"></i>
                    <span>Users</span>
                </a>
                <a href="<?= url('admin/rooms') ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors" onclick="toggleMobileSidebar()">
                    <i class="fas fa-bed mr-3 w-5"></i>
                    <span>Rooms</span>
                </a>
                <a href="<?= url('admin/bookings') ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors" onclick="toggleMobileSidebar()">
                    <i class="fas fa-calendar-check mr-3 w-5"></i>
                    <span>Bookings</span>
                </a>
                <a href="<?= url('admin/orders') ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors" onclick="toggleMobileSidebar()">
                    <i class="fas fa-shopping-cart mr-3 w-5"></i>
                    <span>Orders</span>
                </a>
                <a href="<?= url('admin/tickets') ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors" onclick="toggleMobileSidebar()">
                    <i class="fas fa-ticket-alt mr-3 w-5"></i>
                    <span>Support Tickets</span>
                </a>
                <a href="<?= url('admin/reports') ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors" onclick="toggleMobileSidebar()">
                    <i class="fas fa-chart-bar mr-3 w-5"></i>
                    <span>Reports</span>
                </a>
                <a href="<?= url('admin/settings') ?>" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors" onclick="toggleMobileSidebar()">
                    <i class="fas fa-cog mr-3 w-5"></i>
                    <span>Settings</span>
                </a>

                <div class="border-t border-gray-700 my-4"></div>

                <a href="<?= url('') ?>" target="_blank" class="flex items-center px-4 py-3 text-sm font-medium rounded-lg hover:bg-gray-700 transition-colors">
                    <i class="fas fa-external-link-alt mr-3 w-5"></i>
                    <span>View Website</span>
                </a>
            </nav>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>
        window.BASE_PATH = '<?= url('') ?>';
    </script>
    <script src="<?= asset('js/admin.js') ?>"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', toggleMobileSidebar);
        
        function toggleMobileSidebar() {
            document.getElementById('mobile-sidebar-overlay').classList.toggle('hidden');
        }
        
        // Admin menu toggle
        const adminMenuButton = document.getElementById('admin-menu-button');
        const adminMenu = document.getElementById('admin-menu');
        
        if (adminMenuButton && adminMenu) {
            adminMenuButton.addEventListener('click', function() {
                adminMenu.classList.toggle('hidden');
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!adminMenuButton.contains(event.target) && !adminMenu.contains(event.target)) {
                    adminMenu.classList.add('hidden');
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
    </script>
</body>
</html>
