<?php
$page_title = 'Settings';
ob_start();
?>

<!-- Modern Settings Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-700 to-gray-900 bg-clip-text text-transparent">
                System Settings
            </h1>
            <p class="text-gray-600 mt-2">Configure your application settings</p>
        </div>
    </div>
</div>

<!-- Settings Form -->
<form method="POST" action="<?= url('admin/settings/update') ?>" class="space-y-6">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
    
    <!-- General Settings -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-cog text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">General Settings</h3>
                <p class="text-sm text-gray-600">Basic application configuration</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Site Name</label>
                <input type="text" name="settings[site_name]" value="<?= htmlspecialchars($settings['General']['site_name']['setting_value'] ?? 'Delhi Modern Living') ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Site Email</label>
                <input type="email" name="settings[site_email]" value="<?= htmlspecialchars($settings['General']['site_email']['setting_value'] ?? 'info@delhipg.com') ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Contact Phone</label>
                <input type="text" name="settings[contact_phone]" value="<?= htmlspecialchars($settings['General']['contact_phone']['setting_value'] ?? '+91 9876543210') ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Timezone</label>
                <select name="settings[timezone]" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="Asia/Kolkata" selected>Asia/Kolkata (IST)</option>
                    <option value="UTC">UTC</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Booking Settings -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-calendar-check text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Booking Settings</h3>
                <p class="text-sm text-gray-600">Configure booking and reservation settings</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Minimum Booking Days</label>
                <input type="number" name="settings[min_booking_days]" value="<?= htmlspecialchars($settings['Booking']['min_booking_days']['setting_value'] ?? '30') ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Maximum Booking Days</label>
                <input type="number" name="settings[max_booking_days]" value="<?= htmlspecialchars($settings['Booking']['max_booking_days']['setting_value'] ?? '365') ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Cancellation Period (hours)</label>
                <input type="number" name="settings[cancellation_period]" value="<?= htmlspecialchars($settings['Booking']['cancellation_period']['setting_value'] ?? '24') ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Auto-Approve Bookings</label>
                <select name="settings[auto_approve_bookings]" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                    <option value="yes" <?= ($settings['Booking']['auto_approve_bookings']['setting_value'] ?? 'no') === 'yes' ? 'selected' : '' ?>>Yes</option>
                    <option value="no" <?= ($settings['Booking']['auto_approve_bookings']['setting_value'] ?? 'no') === 'no' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Payment Settings -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-credit-card text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Payment Settings</h3>
                <p class="text-sm text-gray-600">Configure payment gateway settings</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Currency</label>
                <select name="settings[currency]" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="INR" selected>INR (₹)</option>
                    <option value="USD">USD ($)</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tax Rate (%)</label>
                <input type="number" step="0.01" name="settings[tax_rate]" value="<?= htmlspecialchars($settings['Payment']['tax_rate']['setting_value'] ?? '18') ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Enable Online Payment</label>
                <select name="settings[enable_online_payment]" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="yes" <?= ($settings['Payment']['enable_online_payment']['setting_value'] ?? 'yes') === 'yes' ? 'selected' : '' ?>>Yes</option>
                    <option value="no" <?= ($settings['Payment']['enable_online_payment']['setting_value'] ?? 'yes') === 'no' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Enable Cash Payment</label>
                <select name="settings[enable_cash_payment]" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <option value="yes" <?= ($settings['Payment']['enable_cash_payment']['setting_value'] ?? 'yes') === 'yes' ? 'selected' : '' ?>>Yes</option>
                    <option value="no" <?= ($settings['Payment']['enable_cash_payment']['setting_value'] ?? 'yes') === 'no' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
        </div>
    </div>
    
    <!-- Email Settings -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-envelope text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Email Settings</h3>
                <p class="text-sm text-gray-600">Configure email notifications</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Enable Email Notifications</label>
                <select name="settings[enable_email_notifications]" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="yes" <?= ($settings['Email']['enable_email_notifications']['setting_value'] ?? 'yes') === 'yes' ? 'selected' : '' ?>>Yes</option>
                    <option value="no" <?= ($settings['Email']['enable_email_notifications']['setting_value'] ?? 'yes') === 'no' ? 'selected' : '' ?>>No</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Admin Email</label>
                <input type="email" name="settings[admin_email]" value="<?= htmlspecialchars($settings['Email']['admin_email']['setting_value'] ?? 'admin@delhipg.com') ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
        </div>
    </div>
    
    <!-- Maintenance Mode -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-tools text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Maintenance Mode</h3>
                <p class="text-sm text-gray-600">Enable maintenance mode to prevent user access</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Maintenance Mode</label>
                <select name="settings[maintenance_mode]" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent">
                    <option value="off" <?= ($settings['Maintenance']['maintenance_mode']['setting_value'] ?? 'off') === 'off' ? 'selected' : '' ?>>Off</option>
                    <option value="on" <?= ($settings['Maintenance']['maintenance_mode']['setting_value'] ?? 'off') === 'on' ? 'selected' : '' ?>>On</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Maintenance Message</label>
                <input type="text" name="settings[maintenance_message]" value="<?= htmlspecialchars($settings['Maintenance']['maintenance_message']['setting_value'] ?? 'We are currently under maintenance. Please check back soon.') ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent">
            </div>
        </div>
    </div>
    
    <!-- Save Button -->
    <div class="flex justify-end gap-4">
        <a href="<?= url('admin/dashboard') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 font-semibold">
            <i class="fas fa-times mr-2"></i>Cancel
        </a>
        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-semibold shadow-lg">
            <i class="fas fa-save mr-2"></i>Save Settings
        </button>
    </div>
</form>

<?php
$content = ob_get_clean();
include 'views/layouts/admin.php';
?>

