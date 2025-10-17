<?php
$title = 'Reset Password - Delhi Modern Living';
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
                Reset your password
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Enter your new password below
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="<?= url('auth/reset-password') ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
            <input type="hidden" name="token" value="<?= htmlspecialchars($token ?? '') ?>">
            
            <?php if (isset($error)): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <?= htmlspecialchars($error) ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="space-y-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" autocomplete="new-password" required 
                               class="appearance-none relative block w-full px-3 py-2 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                               placeholder="Enter new password (min. 6 characters)"
                               minlength="6">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                    <?php if (isset($errors['password'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                    <div class="mt-1 text-xs text-gray-500">
                        Password must be at least 6 characters long
                    </div>
                </div>
                
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                    <div class="mt-1 relative">
                        <input id="confirm_password" name="confirm_password" type="password" autocomplete="new-password" required 
                               class="appearance-none relative block w-full px-3 py-2 pl-10 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                               placeholder="Confirm your new password"
                               minlength="6">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                    </div>
                    <?php if (isset($errors['confirm_password'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= $errors['confirm_password'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Password Strength Indicator -->
            <div class="password-strength hidden">
                <div class="text-xs text-gray-600 mb-1">Password strength:</div>
                <div class="flex space-x-1">
                    <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                    <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                    <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                    <div class="h-1 flex-1 bg-gray-200 rounded"></div>
                </div>
                <div class="text-xs mt-1 strength-text"></div>
            </div>

            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-300">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-key text-primary-500 group-hover:text-primary-400"></i>
                    </span>
                    Reset Password
                </button>
            </div>
            
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    Remember your password?
                    <a href="<?= url('auth/login') ?>" class="font-medium text-primary-600 hover:text-primary-500">
                        Sign in here
                    </a>
                </p>
            </div>
        </form>
        
        <!-- Security Notice -->
        <div class="mt-8 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-shield-alt text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-yellow-800">Security Tips</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc list-inside space-y-1">
                            <li>Use a strong, unique password</li>
                            <li>Include uppercase, lowercase, numbers, and symbols</li>
                            <li>Don't reuse passwords from other accounts</li>
                            <li>Consider using a password manager</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
        this.classList.add('border-red-500');
    } else {
        this.setCustomValidity('');
        this.classList.remove('border-red-500');
    }
});

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthIndicator = document.querySelector('.password-strength');
    const strengthBars = strengthIndicator.querySelectorAll('.h-1');
    const strengthText = strengthIndicator.querySelector('.strength-text');
    
    if (password.length > 0) {
        strengthIndicator.classList.remove('hidden');
        
        let strength = 0;
        let strengthLabel = '';
        
        // Length check
        if (password.length >= 8) strength++;
        
        // Uppercase check
        if (/[A-Z]/.test(password)) strength++;
        
        // Lowercase check
        if (/[a-z]/.test(password)) strength++;
        
        // Number check
        if (/\d/.test(password)) strength++;
        
        // Special character check
        if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;
        
        // Reset bars
        strengthBars.forEach(bar => {
            bar.className = 'h-1 flex-1 bg-gray-200 rounded';
        });
        
        // Set strength
        if (strength <= 1) {
            strengthBars[0].classList.add('bg-red-500');
            strengthLabel = 'Very Weak';
        } else if (strength <= 2) {
            strengthBars[0].classList.add('bg-orange-500');
            strengthBars[1].classList.add('bg-orange-500');
            strengthLabel = 'Weak';
        } else if (strength <= 3) {
            strengthBars[0].classList.add('bg-yellow-500');
            strengthBars[1].classList.add('bg-yellow-500');
            strengthBars[2].classList.add('bg-yellow-500');
            strengthLabel = 'Fair';
        } else if (strength <= 4) {
            strengthBars[0].classList.add('bg-blue-500');
            strengthBars[1].classList.add('bg-blue-500');
            strengthBars[2].classList.add('bg-blue-500');
            strengthBars[3].classList.add('bg-blue-500');
            strengthLabel = 'Good';
        } else {
            strengthBars.forEach(bar => bar.classList.add('bg-green-500'));
            strengthLabel = 'Strong';
        }
        
        strengthText.textContent = strengthLabel;
    } else {
        strengthIndicator.classList.add('hidden');
    }
});
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/app.php';
?>
