<?php
$page_title = 'Create User';
ob_start();
?>

<!-- Modern User Creation Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
                Create New User
            </h1>
            <p class="text-gray-600 mt-2">Add a new user to the system</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= url('admin/users') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span class="font-semibold">Back to Users</span>
            </a>
        </div>
    </div>
</div>

<!-- User Creation Form -->
<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
    <form method="POST" action="<?= url('admin/users/create') ?>" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        
        <!-- Basic Information Section -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Basic Information</h3>
                    <p class="text-sm text-gray-600">Enter the user's personal details</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                           placeholder="Enter full name">
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                           placeholder="Enter email address">
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input type="tel" id="phone" name="phone"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                           placeholder="Enter phone number">
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                           placeholder="Enter password">
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
                    <p class="text-sm text-gray-600">Configure user permissions and status</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                        User Role <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select Role</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Account Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select Status</option>
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                
                <div>
                    <label for="email_verified" class="block text-sm font-semibold text-gray-700 mb-2">
                        Email Verification
                    </label>
                    <select id="email_verified" name="email_verified"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="0">Not Verified</option>
                        <option value="1" selected>Verified</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Additional Information Section -->
        <div class="pb-6">
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
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                </div>
                
                <div>
                    <label for="gender" class="block text-sm font-semibold text-gray-700 mb-2">
                        Gender
                    </label>
                    <select id="gender" name="gender"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select Gender</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                        <option value="prefer_not_to_say">Prefer not to say</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                    Address
                </label>
                <textarea id="address" name="address" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                          placeholder="Enter full address"></textarea>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
            <a href="<?= url('admin/users') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 font-semibold">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-xl hover:from-green-700 hover:to-blue-700 transition-all duration-300 font-semibold shadow-lg">
                <i class="fas fa-save mr-2"></i>Create User
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
    
    // Email validation
    emailInput.addEventListener('blur', function() {
        const email = this.value;
        if (email) {
            // Check if email already exists
            fetch('<?= url('api/check-email') ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email: email })
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
    
    // Password strength validation
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const minLength = 8;
        
        if (password.length < minLength) {
            this.setCustomValidity(`Password must be at least ${minLength} characters long`);
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
