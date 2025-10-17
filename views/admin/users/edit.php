<?php
$page_title = 'Edit User';
ob_start();
?>

<!-- Modern User Edit Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                Edit User
            </h1>
            <p class="text-gray-600 mt-2">Update user information and settings</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= url('admin/users/' . $user['id']) ?>" class="px-6 py-3 bg-blue-100 text-blue-700 rounded-xl hover:bg-blue-200 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-eye"></i>
                <span class="font-semibold">View User</span>
            </a>
            <a href="<?= url('admin/users') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span class="font-semibold">Back to Users</span>
            </a>
        </div>
    </div>
</div>

<!-- User Edit Form -->
<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
    <form method="POST" action="<?= url('admin/users/' . $user['id'] . '/edit') ?>" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        <input type="hidden" name="_method" value="PUT">
        
        <!-- Basic Information Section -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Basic Information</h3>
                    <p class="text-sm text-gray-600">Update the user's personal details</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" required
                           value="<?= htmlspecialchars($user['name']) ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                           placeholder="Enter full name">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" required
                           value="<?= htmlspecialchars($user['email']) ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                           placeholder="Enter email address">
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input type="tel" id="phone" name="phone"
                           value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                           placeholder="Enter phone number">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        New Password
                        <span class="text-gray-500 text-xs">(Leave blank to keep current)</span>
                    </label>
                    <input type="password" id="password" name="password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                           placeholder="Enter new password">
                </div>
            </div>
        </div>
        
        <!-- Account Settings Section -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-cog text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Account Settings</h3>
                    <p class="text-sm text-gray-600">Update user permissions and status</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                        User Role <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select Role</option>
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Account Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select Status</option>
                        <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        <option value="suspended" <?= $user['status'] === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                    </select>
                </div>
                
                <div>
                    <label for="email_verified" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Verification
                    </label>
                    <select id="email_verified" name="email_verified"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="0" <?= !$user['email_verified'] ? 'selected' : '' ?>>Not Verified</option>
                        <option value="1" <?= $user['email_verified'] ? 'selected' : '' ?>>Verified</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Additional Information Section -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-info-circle text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Additional Information</h3>
                    <p class="text-sm text-gray-600">Optional user details</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="date_of_birth" class="block text-sm font-semibold text-gray-700 mb-2">
                        Date of Birth
                    </label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                           value="<?= htmlspecialchars($user['date_of_birth'] ?? '') ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                </div>
                
                <div>
                    <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">
                        Gender
                    </label>
                    <select id="gender" name="gender"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select Gender</option>
                        <option value="male" <?= ($user['gender'] ?? '') === 'male' ? 'selected' : '' ?>>Male</option>
                        <option value="female" <?= ($user['gender'] ?? '') === 'female' ? 'selected' : '' ?>>Female</option>
                        <option value="other" <?= ($user['gender'] ?? '') === 'other' ? 'selected' : '' ?>>Other</option>
                        <option value="prefer_not_to_say" <?= ($user['gender'] ?? '') === 'prefer_not_to_say' ? 'selected' : '' ?>>Prefer not to say</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                    Address
                </label>
                <textarea id="address" name="address" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-300"
                          placeholder="Enter full address"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>
            </div>
        </div>
        
        <!-- Account Statistics (Read-only) -->
        <div class="pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Account Information</h3>
                    <p class="text-sm text-gray-600">Read-only account statistics</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="p-4 bg-gray-50 rounded-xl">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Member Since</label>
                    <p class="text-lg font-medium text-gray-900"><?= date('M d, Y', strtotime($user['created_at'])) ?></p>
                </div>
                
                <div class="p-4 bg-gray-50 rounded-xl">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Last Login</label>
                    <p class="text-lg font-medium text-gray-900"><?= $user['last_login'] ? date('M d, Y', strtotime($user['last_login'])) : 'Never' ?></p>
                </div>
                
                <div class="p-4 bg-gray-50 rounded-xl">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Last Updated</label>
                    <p class="text-lg font-medium text-gray-900"><?= date('M d, Y', strtotime($user['updated_at'])) ?></p>
                </div>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
            <a href="<?= url('admin/users/' . $user['id']) ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 font-semibold">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-orange-600 to-red-600 text-white rounded-xl hover:from-orange-700 hover:to-red-700 transition-all duration-300 font-semibold shadow-lg">
                <i class="fas fa-save mr-2"></i>Update User
            </button>
        </div>
    </form>
</div>

<!-- Form Validation Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const originalEmail = '<?= htmlspecialchars($user['email']) ?>';
    
    // Email validation (only if changed)
    emailInput.addEventListener('blur', function() {
        const email = this.value;
        if (email && email !== originalEmail) {
            // Check if email already exists
            fetch('<?= url('api/check-email') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email, exclude_id: <?= $user['id'] ?> })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    this.setCustomValidity('Email already exists');
                    this.classList.add('border-red-500');
                } else {
                    this.setCustomValidity('');
                    this.classList.remove('border-red-500');
                }
            });
        }
    });
    
    // Password strength validation (only if provided)
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        if (password) {
            const minLength = 8;
            
            if (password.length < minLength) {
                this.setCustomValidity(`Password must be at least ${minLength} characters long`);
            } else {
                this.setCustomValidity('');
            }
        } else {
            this.setCustomValidity('');
        }
    });
    
    // Form submission
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields');
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/admin.php';
?>
