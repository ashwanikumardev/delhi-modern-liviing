<?php
$title = 'Forgot Password - Delhi Modern Living';
ob_start();
?>

<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-auto flex justify-center">
                <img class="h-12 w-auto" src="<?= asset('images/logo.png') ?>" alt="Delhi Modern Living" onerror="this.style.display='none'">
                <span class="ml-2 text-2xl font-bold text-primary-600">Delhi Modern Living</span>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Forgot your password?
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Enter your email address and we'll send you a link to reset your password.
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="<?= url('auth/forgot-password') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <?= htmlspecialchars($success) ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <div class="mt-1 relative">
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="appearance-none relative block w-full px-3 py-2 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                           placeholder="Enter your email address"
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
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-300">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-paper-plane text-primary-500 group-hover:text-primary-400"></i>
                    </span>
                    Send Reset Link
                </button>
            </div>
            
            <div class="text-center space-y-2">
                <p class="text-sm text-gray-600">
                    Remember your password?
                    <a href="<?= url('auth/login') ?>" class="font-medium text-primary-600 hover:text-primary-500">
                        Sign in here
                    </a>
                </p>
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="<?= url('auth/signup') ?>" class="font-medium text-primary-600 hover:text-primary-500">
                        Create one now
                    </a>
                </p>
            </div>
        </form>
        
        <!-- Help Section -->
        <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">Need help?</h3>
                    <div class="mt-2 text-sm text-blue-700">
                        <p>If you don't receive the reset email within a few minutes:</p>
                        <ul class="mt-1 list-disc list-inside space-y-1">
                            <li>Check your spam/junk folder</li>
                            <li>Make sure you entered the correct email address</li>
                            <li>Contact our support team for assistance</li>
                        </ul>
                    </div>
                    <div class="mt-3 text-sm">
                        <a href="mailto:support@delhimodernliving.com" class="font-medium text-blue-600 hover:text-blue-500">
                            Contact Support <i class="fas fa-external-link-alt ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'views/layouts/app.php';
?>
