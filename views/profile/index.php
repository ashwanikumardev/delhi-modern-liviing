<?php
$title = 'My Profile - Delhi Modern Living';
ob_start();
?>

<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-600 mt-2">Manage your account settings and view your activity</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Profile Stats -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="text-center">
                        <div class="w-20 h-20 bg-primary-500 rounded-full flex items-center justify-center text-white text-2xl font-bold mx-auto mb-4">
                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-900"><?= htmlspecialchars($user['name']) ?></h2>
                        <p class="text-gray-600"><?= htmlspecialchars($user['email']) ?></p>
                        <div class="mt-4 flex items-center justify-center text-sm text-gray-500">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Member since <?= date('M Y', strtotime($stats['member_since'])) ?>
                        </div>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="space-y-4">
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-calendar-check text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Bookings</p>
                                <p class="text-xl font-semibold text-gray-900"><?= $stats['total_bookings'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-home text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Active Bookings</p>
                                <p class="text-xl font-semibold text-gray-900"><?= $stats['active_bookings'] ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-rupee-sign text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600">Total Spent</p>
                                <p class="text-xl font-semibold text-gray-900">₹<?= number_format($stats['total_spent']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Bookings -->
                <?php if (!empty($stats['recent_bookings'])): ?>
                    <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Bookings</h3>
                        <div class="space-y-3">
                            <?php foreach ($stats['recent_bookings'] as $booking): ?>
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            <?= htmlspecialchars($booking['room_title']) ?>
                                        </p>
                                        <p class="text-xs text-gray-600">
                                            <?= date('M d, Y', strtotime($booking['created_at'])) ?>
                                        </p>
                                    </div>
                                    <span class="text-sm font-semibold text-primary-600">
                                        ₹<?= number_format($booking['total_amount']) ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="<?= url('orders') ?>" class="text-sm text-primary-600 hover:text-primary-500">
                                View all bookings <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Profile Forms -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Personal Information</h2>
                    
                    <form action="<?= url('profile') ?>" method="POST" class="space-y-6">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <input type="hidden" name="action" value="update_info">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" id="name" name="name" required
                                       value="<?= htmlspecialchars($user['name']) ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" id="email" name="email" required
                                       value="<?= htmlspecialchars($user['email']) ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="tel" id="phone" name="phone" required
                                       value="<?= htmlspecialchars($user['phone']) ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Account Status</label>
                                <input type="text" id="status" name="status" readonly
                                       value="<?= ucfirst($user['status']) ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600">
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-primary-600 text-white hover:bg-primary-700 px-6 py-2 rounded-md font-medium transition duration-300">
                                <i class="fas fa-save mr-2"></i>
                                Update Information
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Change Password -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Change Password</h2>
                    
                    <form action="<?= url('profile') ?>" method="POST" class="space-y-6">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                        <input type="hidden" name="action" value="change_password">
                        
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                <input type="password" id="new_password" name="new_password" required minlength="6"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <p class="text-xs text-gray-500 mt-1">Minimum 6 characters</p>
                            </div>
                            
                            <div>
                                <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" required minlength="6"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>
                        
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-red-600 text-white hover:bg-red-700 px-6 py-2 rounded-md font-medium transition duration-300">
                                <i class="fas fa-key mr-2"></i>
                                Change Password
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Account Settings -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Account Settings</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="font-medium text-gray-900">Email Notifications</h3>
                                <p class="text-sm text-gray-600">Receive booking confirmations and updates</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="font-medium text-gray-900">SMS Notifications</h3>
                                <p class="text-sm text-gray-600">Receive SMS alerts for important updates</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h3 class="font-medium text-gray-900">Marketing Communications</h3>
                                <p class="text-sm text-gray-600">Receive offers and promotional content</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                            </label>
                        </div>
                    </div>
                </div>
                
                <!-- Danger Zone -->
                <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
                    <h2 class="text-xl font-semibold text-red-900 mb-4">Danger Zone</h2>
                    <p class="text-gray-600 mb-4">
                        Once you delete your account, there is no going back. Please be certain.
                    </p>
                    <button onclick="confirmDeleteAccount()" 
                            class="bg-red-600 text-white hover:bg-red-700 px-6 py-2 rounded-md font-medium transition duration-300">
                        <i class="fas fa-trash mr-2"></i>
                        Delete Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Password confirmation validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('new_password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
        this.classList.add('border-red-500');
    } else {
        this.setCustomValidity('');
        this.classList.remove('border-red-500');
    }
});

// New password validation
document.getElementById('new_password').addEventListener('input', function() {
    const confirmPassword = document.getElementById('confirm_password');
    if (confirmPassword.value && this.value !== confirmPassword.value) {
        confirmPassword.classList.add('border-red-500');
    } else {
        confirmPassword.classList.remove('border-red-500');
    }
});

function confirmDeleteAccount() {
    if (confirm('Are you sure you want to delete your account? This action cannot be undone and all your data will be permanently removed.')) {
        if (confirm('This is your final warning. Are you absolutely sure you want to delete your account?')) {
            // In a real implementation, this would handle account deletion
            Utils.showToast('Account deletion functionality will be implemented with proper verification', 'info');
        }
    }
}

// Form submission validation
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const action = this.querySelector('input[name="action"]').value;
        
        if (action === 'change_password') {
            const newPassword = this.querySelector('#new_password').value;
            const confirmPassword = this.querySelector('#confirm_password').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                Utils.showToast('New passwords do not match', 'error');
                return;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                Utils.showToast('New password must be at least 6 characters long', 'error');
                return;
            }
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/app.php';
?>
