<?php
$title = 'Sign Up - Delhi Modern Living';
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
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                <a href="<?= url('auth/login') ?>" class="font-medium text-primary-600 hover:text-primary-500">
                    sign in to your existing account
                </a>
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="<?= url('auth/signup') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>
            
            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input id="name" name="name" type="text" autocomplete="name" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                           placeholder="Enter your full name"
                           value="<?= htmlspecialchars($old['name'] ?? '') ?>">
                    <?php if (isset($errors['name'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['name'] ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                           placeholder="Enter your email address"
                           value="<?= htmlspecialchars($old['email'] ?? '') ?>">
                    <?php if (isset($errors['email'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['email'] ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                    <input id="phone" name="phone" type="tel" autocomplete="tel" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                           placeholder="Enter your phone number"
                           value="<?= htmlspecialchars($old['phone'] ?? '') ?>">
                    <?php if (isset($errors['phone'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['phone'] ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                           placeholder="Create a password (min. 6 characters)">
                    <?php if (isset($errors['password'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" required 
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                           placeholder="Confirm your password">
                </div>
            </div>

            <div class="flex items-center">
                <input id="terms" name="terms" type="checkbox" required
                       class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-sm text-gray-900">
                    I agree to the 
                    <a href="<?= url('terms') ?>" class="text-primary-600 hover:text-primary-500" target="_blank">Terms and Conditions</a>
                    and 
                    <a href="<?= url('privacy') ?>" class="text-primary-600 hover:text-primary-500" target="_blank">Privacy Policy</a>
                </label>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus text-primary-500 group-hover:text-primary-400"></i>
                    </span>
                    Create Account
                </button>
            </div>
            
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="<?= url('auth/login') ?>" class="font-medium text-primary-600 hover:text-primary-500">
                        Sign in here
                    </a>
                </p>
            </div>
        </form>
    </div>
</div>

<script>
// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/app.php';
?>
