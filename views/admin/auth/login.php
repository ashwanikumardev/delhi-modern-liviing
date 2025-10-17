<?php
// Include helper functions if not already loaded
if (!function_exists('url')) {
    require_once __DIR__ . '/../../../helpers/url_helper.php';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Delhi Modern Living</title>
    
    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8 p-8">
        <div>
            <div class="mx-auto h-12 w-auto flex justify-center items-center">
                <img class="h-12 w-auto" src="<?= asset('images/logo-white.png') ?>" alt="Delhi Modern Living" onerror="this.style.display='none'">
                <span class="ml-2 text-2xl font-bold text-white">Admin Panel</span>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white">
                Admin Login
            </h2>
            <p class="mt-2 text-center text-sm text-gray-400">
                Access the administrative dashboard
            </p>
        </div>
        
        <div class="bg-white rounded-lg shadow-xl p-8">
            <form class="space-y-6" action="<?= url('admin/login') ?>" method="POST">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                
                <?php if (isset($error)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <?= htmlspecialchars($error) ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <div class="mt-1 relative">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="appearance-none relative block w-full px-3 py-2 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                               placeholder="Enter your admin email"
                               value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                    </div>
                    <?php if (isset($errors['email'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['email'] ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                               class="appearance-none relative block w-full px-3 py-2 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                               placeholder="Enter your password">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                    <?php if (isset($errors['password'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" 
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                </div>

                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-300">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-primary-500 group-hover:text-primary-400"></i>
                        </span>
                        Sign in to Admin Panel
                    </button>
                </div>
                
                <div class="text-center">
                    <a href="<?= url('/') ?>" class="text-sm text-primary-600 hover:text-primary-500">
                        <i class="fas fa-arrow-left mr-1"></i>
                        Back to Website
                    </a>
                </div>
            </form>
        </div>
        
        <!-- Security Notice -->
        <div class="bg-yellow-900 bg-opacity-50 border border-yellow-600 rounded-lg p-4">
            <div class="flex items-center text-yellow-200">
                <i class="fas fa-shield-alt mr-2"></i>
                <span class="text-sm">
                    This is a secure admin area. All activities are logged and monitored.
                </span>
            </div>
        </div>
    </div>
    
    <!-- Background Pattern -->
    <div class="fixed inset-0 z-[-1]">
        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-blue-900 to-gray-900"></div>
        <div class="absolute inset-0 bg-black opacity-50"></div>
    </div>
</body>
</html>
