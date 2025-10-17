<?php
$page_title = 'User Details';
ob_start();
?>

<!-- Modern User Details Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                User Details
            </h1>
            <p class="text-gray-600 mt-2">View and manage user information</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= url('admin/users/' . $user['id'] . '/edit') ?>" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 flex items-center gap-2 shadow-lg">
                <i class="fas fa-edit"></i>
                <span class="font-semibold">Edit User</span>
            </a>
            <a href="<?= url('admin/users') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span class="font-semibold">Back to Users</span>
            </a>
        </div>
    </div>
</div>

<!-- User Information Cards -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Basic Information -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-user text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Basic Information</h3>
                <p class="text-sm text-gray-600">User account details</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                <p class="text-lg text-gray-900 font-medium"><?= htmlspecialchars($user['name']) ?></p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <p class="text-lg text-gray-900"><?= htmlspecialchars($user['email']) ?></p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                <p class="text-lg text-gray-900"><?= htmlspecialchars($user['phone'] ?? 'Not provided') ?></p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">User Role</label>
                <span class="px-3 py-1 rounded-full text-sm font-bold <?= $user['role'] === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' ?>">
                    <i class="fas fa-<?= $user['role'] === 'admin' ? 'crown' : 'user' ?> mr-1"></i>
                    <?= ucfirst($user['role']) ?>
                </span>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Account Status</label>
                <span class="px-3 py-1 rounded-full text-sm font-bold <?= $user['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                    <i class="fas fa-circle text-xs mr-1"></i>
                    <?= ucfirst($user['status']) ?>
                </span>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email Verified</label>
                <span class="px-3 py-1 rounded-full text-sm font-bold <?= $user['email_verified'] ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                    <i class="fas fa-<?= $user['email_verified'] ? 'check-circle' : 'clock' ?> mr-1"></i>
                    <?= $user['email_verified'] ? 'Verified' : 'Pending' ?>
                </span>
            </div>
        </div>
    </div>
    
    <!-- Account Statistics -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-chart-bar text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Statistics</h3>
                <p class="text-sm text-gray-600">Account activity</p>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-check text-blue-600"></i>
                    <span class="text-sm font-medium text-gray-700">Total Bookings</span>
                </div>
                <span class="text-lg font-bold text-gray-900"><?= count($bookings ?? []) ?></span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-2">
                    <i class="fas fa-rupee-sign text-green-600"></i>
                    <span class="text-sm font-medium text-gray-700">Total Spent</span>
                </div>
                <span class="text-lg font-bold text-gray-900">₹<?= number_format(array_sum(array_column($bookings ?? [], 'total_amount')) ?? 0) ?></span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock text-purple-600"></i>
                    <span class="text-sm font-medium text-gray-700">Member Since</span>
                </div>
                <span class="text-sm font-medium text-gray-900"><?= isset($user['created_at']) && $user['created_at'] ? date('M d, Y', strtotime($user['created_at'])) : 'Unknown' ?></span>
            </div>
            
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center gap-2">
                    <i class="fas fa-sign-in-alt text-orange-600"></i>
                    <span class="text-sm font-medium text-gray-700">Last Login</span>
                </div>
                <span class="text-sm font-medium text-gray-900"><?= isset($user['last_login']) && $user['last_login'] ? date('M d, Y', strtotime($user['last_login'])) : 'Never' ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-list text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Recent Bookings</h3>
                <p class="text-sm text-gray-600">Latest booking activity</p>
            </div>
        </div>
        <a href="<?= url('admin/bookings?user_id=' . $user['id']) ?>" class="text-blue-600 hover:text-blue-700 font-semibold">
            View All <i class="fas fa-arrow-right ml-1"></i>
        </a>
    </div>
    
    <?php if (!empty($bookings)): ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Booking ID</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Room</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Check-in</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach (array_slice($bookings, 0, 5) as $booking): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-gray-900">#<?= $booking['id'] ?></span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900"><?= htmlspecialchars($booking['room_title'] ?? 'N/A') ?></p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-900"><?= isset($booking['start_date']) && $booking['start_date'] ? date('M d, Y', strtotime($booking['start_date'])) : 'N/A' ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-gray-900">₹<?= number_format($booking['total_amount']) ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    <?= $booking['booking_status'] === 'confirmed' ? 'bg-green-100 text-green-700' : 
                                        ($booking['booking_status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') ?>">
                                    <?= ucfirst($booking['booking_status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="<?= url('admin/bookings/' . $booking['id'] . '/view') ?>" class="text-blue-600 hover:text-blue-700 font-semibold">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="text-center py-12">
            <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
            <p class="text-gray-500 text-lg">No bookings found</p>
            <p class="text-gray-400 text-sm">This user hasn't made any bookings yet.</p>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'views/layouts/admin.php';
?>
