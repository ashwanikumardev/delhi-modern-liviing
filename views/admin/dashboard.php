<?php
$title = 'Admin Dashboard - Delhi Modern Living';
$page_title = 'Dashboard';
ob_start();
?>

<!-- Modern Dashboard Header -->
<div class="mb-6 lg:mb-8">
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl lg:text-3xl font-bold bg-gradient-to-r from-primary-600 to-blue-600 bg-clip-text text-transparent">
                Dashboard Overview
            </h1>
            <p class="text-gray-600 mt-2 text-sm lg:text-base">Welcome back! Here's what's happening with your PG business today.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="<?= url('admin/bookings') ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-300 flex items-center justify-center gap-2 text-sm lg:text-base">
                <i class="fas fa-calendar-alt text-primary-600"></i>
                <span class="font-semibold">View Bookings</span>
            </a>
            <a href="<?= url('admin/rooms/create') ?>" class="px-4 py-2 bg-gradient-to-r from-primary-600 to-blue-600 text-white rounded-xl hover:from-primary-700 hover:to-blue-700 transition-all duration-300 flex items-center justify-center gap-2 shadow-lg text-sm lg:text-base">
                <i class="fas fa-plus"></i>
                <span class="font-semibold">Add Room</span>
            </a>
        </div>
    </div>
</div>

<!-- Modern Dashboard Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8 admin-stat-grid">
    <!-- Total Users Card -->
    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 transform hover:-translate-y-1">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-users text-white text-2xl"></i>
                </div>
                <a href="<?= url('admin/users') ?>" class="text-blue-600 hover:text-blue-700 transition-colors">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Users</p>
                <p class="text-3xl font-bold text-gray-900"><?= number_format($user_stats['total_users'] ?? 0) ?></p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-3 border-t border-blue-200">
            <div class="flex items-center justify-between text-sm">
                <span class="text-blue-700 font-semibold">
                    <i class="fas fa-check-circle mr-1"></i>
                    <?= number_format($user_stats['active_users'] ?? 0) ?> Active
                </span>
                <span class="text-blue-600">
                    <i class="fas fa-user-plus mr-1"></i>
                    <?= number_format($user_stats['new_users_today'] ?? 0) ?> Today
                </span>
            </div>
        </div>
    </div>

    <!-- Total Rooms Card -->
    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 transform hover:-translate-y-1">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-bed text-white text-2xl"></i>
                </div>
                <a href="<?= url('admin/rooms') ?>" class="text-green-600 hover:text-green-700 transition-colors">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Rooms</p>
                <p class="text-3xl font-bold text-gray-900"><?= number_format($room_stats['total_rooms'] ?? 0) ?></p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-green-50 to-green-100 px-6 py-3 border-t border-green-200">
            <div class="flex items-center justify-between text-sm">
                <span class="text-green-700 font-semibold">
                    <i class="fas fa-check-circle mr-1"></i>
                    <?= number_format($room_stats['available_rooms'] ?? 0) ?> Available
                </span>
                <span class="text-green-600">
                    <i class="fas fa-door-closed mr-1"></i>
                    <?= number_format($room_stats['occupied_rooms'] ?? 0) ?> Occupied
                </span>
            </div>
        </div>
    </div>

    <!-- Total Bookings Card -->
    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 transform hover:-translate-y-1">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-calendar-check text-white text-2xl"></i>
                </div>
                <a href="<?= url('admin/bookings') ?>" class="text-yellow-600 hover:text-yellow-700 transition-colors">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Bookings</p>
                <p class="text-3xl font-bold text-gray-900"><?= number_format($order_stats['total_bookings'] ?? 0) ?></p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-yellow-50 to-orange-100 px-6 py-3 border-t border-yellow-200">
            <div class="flex items-center justify-between text-sm">
                <span class="text-yellow-700 font-semibold">
                    <i class="fas fa-check-circle mr-1"></i>
                    <?= number_format($order_stats['confirmed_bookings'] ?? 0) ?> Confirmed
                </span>
                <span class="text-orange-600">
                    <i class="fas fa-clock mr-1"></i>
                    <?= number_format($order_stats['pending_bookings'] ?? 0) ?> Pending
                </span>
            </div>
        </div>
    </div>

    <!-- Total Revenue Card -->
    <div class="group bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 transform hover:-translate-y-1">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-rupee-sign text-white text-2xl"></i>
                </div>
                <a href="<?= url('admin/reports') ?>" class="text-purple-600 hover:text-purple-700 transition-colors">
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Total Revenue</p>
                <p class="text-3xl font-bold text-gray-900">₹<?= number_format($order_stats['total_revenue'] ?? 0) ?></p>
            </div>
        </div>
        <div class="bg-gradient-to-r from-purple-50 to-purple-100 px-6 py-3 border-t border-purple-200">
            <div class="flex items-center justify-between text-sm">
                <span class="text-purple-700 font-semibold">
                    <i class="fas fa-chart-line mr-1"></i>
                    This Month
                </span>
                <span class="text-purple-600">
                    ₹<?= number_format($order_stats['pending_revenue'] ?? 0) ?> Pending
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Charts and Analytics Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Monthly Revenue Chart -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-primary-50 to-blue-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">
                    <i class="fas fa-chart-line text-primary-600 mr-2"></i>
                    Monthly Revenue Trend
                </h3>
                <a href="<?= url('admin/reports') ?>" class="text-sm text-primary-600 hover:text-primary-700 font-semibold">
                    View Details <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performing Rooms -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">
                    <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                    Top Performing Rooms
                </h3>
                <a href="<?= url('admin/rooms') ?>" class="text-sm text-green-600 hover:text-green-700 font-semibold">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <?php if (!empty($top_rooms)): ?>
                    <?php foreach (array_slice($top_rooms, 0, 5) as $index => $room): ?>
                        <div class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center gap-3 flex-1">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                    <span class="text-white font-bold"><?= $index + 1 ?></span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900"><?= htmlspecialchars($room['title']) ?></p>
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-calendar-check mr-1"></i>
                                        <?= $room['booking_count'] ?> bookings • ₹<?= number_format($room['total_revenue'] ?? 0) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="w-24">
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-primary-500 to-blue-600 h-2 rounded-full transition-all duration-500"
                                         style="width: <?= min(100, ($room['booking_count'] / max(1, $top_rooms[0]['booking_count'])) * 100) ?>%"></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-8">
                        <i class="fas fa-chart-bar text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">No booking data available yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Bookings -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">
                    <i class="fas fa-calendar-alt text-yellow-600 mr-2"></i>
                    Recent Bookings
                </h3>
                <a href="<?= url('admin/bookings') ?>" class="text-sm text-yellow-600 hover:text-yellow-700 font-semibold">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            <?php if (!empty($recent_orders)): ?>
                <?php foreach ($recent_orders as $order): ?>
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fas fa-bed text-primary-600 text-sm"></i>
                                    <p class="text-sm font-semibold text-gray-900 truncate">
                                        <?= htmlspecialchars($order['room_title']) ?>
                                    </p>
                                </div>
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fas fa-user text-gray-400 text-xs"></i>
                                    <p class="text-xs text-gray-600">
                                        <?= htmlspecialchars($order['user_name']) ?>
                                    </p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-clock text-gray-400 text-xs"></i>
                                    <p class="text-xs text-gray-500">
                                        <?= date('M d, Y • h:i A', strtotime($order['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                    <?= $order['payment_status'] === 'paid' ? 'bg-green-100 text-green-700' :
                                        ($order['payment_status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') ?>">
                                    <i class="fas fa-<?= $order['payment_status'] === 'paid' ? 'check-circle' :
                                        ($order['payment_status'] === 'pending' ? 'clock' : 'times-circle') ?> mr-1"></i>
                                    <?= ucfirst($order['payment_status']) ?>
                                </span>
                                <span class="text-sm font-bold text-gray-900">
                                    ₹<?= number_format($order['total_amount']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-calendar-times text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500">No recent bookings</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
            <a href="<?= url('admin/bookings') ?>" class="flex items-center justify-center gap-2 text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                <span>View All Bookings</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-900">
                    <i class="fas fa-user-plus text-blue-600 mr-2"></i>
                    Recent Users
                </h3>
                <a href="<?= url('admin/users') ?>" class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="divide-y divide-gray-100">
            <?php if (!empty($recent_users)): ?>
                <?php foreach ($recent_users as $user): ?>
                    <div class="px-6 py-4 hover:bg-gray-50 transition-colors duration-200">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-3">
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-lg font-bold shadow-lg">
                                    <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <p class="text-sm font-semibold text-gray-900">
                                            <?= htmlspecialchars($user['name']) ?>
                                        </p>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold
                                            <?= $user['status'] === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                            <i class="fas fa-circle text-xs mr-1"></i>
                                            <?= ucfirst($user['status']) ?>
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <i class="fas fa-envelope text-gray-400 text-xs"></i>
                                        <p class="text-xs text-gray-600">
                                            <?= htmlspecialchars($user['email']) ?>
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar text-gray-400 text-xs"></i>
                                        <p class="text-xs text-gray-500">
                                            Joined <?= date('M d, Y', strtotime($user['created_at'])) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="px-6 py-12 text-center">
                    <i class="fas fa-user-slash text-gray-300 text-4xl mb-3"></i>
                    <p class="text-gray-500">No recent users</p>
                </div>
            <?php endif; ?>
        </div>
        <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
            <a href="<?= url('admin/users') ?>" class="flex items-center justify-center gap-2 text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
                <span>View All Users</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Quick Actions Section -->
<div class="mt-8">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold text-gray-900">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
            Quick Actions
        </h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="<?= url('admin/rooms/create') ?>" class="group bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 transform hover:-translate-y-1">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-plus text-white text-xl"></i>
            </div>
            <p class="text-sm font-bold text-gray-900 text-center">Add New Room</p>
            <p class="text-xs text-gray-500 text-center mt-1">Create a new listing</p>
        </a>

        <a href="<?= url('admin/users') ?>" class="group bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 transform hover:-translate-y-1">
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-users text-white text-xl"></i>
            </div>
            <p class="text-sm font-bold text-gray-900 text-center">Manage Users</p>
            <p class="text-xs text-gray-500 text-center mt-1">View all users</p>
        </a>

        <a href="<?= url('admin/bookings') ?>" class="group bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 transform hover:-translate-y-1">
            <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-calendar-check text-white text-xl"></i>
            </div>
            <p class="text-sm font-bold text-gray-900 text-center">View Bookings</p>
            <p class="text-xs text-gray-500 text-center mt-1">Manage reservations</p>
        </a>

        <a href="<?= url('admin/reports') ?>" class="group bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 transform hover:-translate-y-1">
            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-chart-bar text-white text-xl"></i>
            </div>
            <p class="text-sm font-bold text-gray-900 text-center">View Reports</p>
            <p class="text-xs text-gray-500 text-center mt-1">Analytics & insights</p>
        </a>

        <a href="<?= url('admin/rooms') ?>" class="group bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 transform hover:-translate-y-1">
            <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-bed text-white text-xl"></i>
            </div>
            <p class="text-sm font-bold text-gray-900 text-center">Manage Rooms</p>
            <p class="text-xs text-gray-500 text-center mt-1">Edit room listings</p>
        </a>

        <a href="<?= url('admin/tickets') ?>" class="group bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 transform hover:-translate-y-1">
            <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-ticket-alt text-white text-xl"></i>
            </div>
            <p class="text-sm font-bold text-gray-900 text-center">Support Tickets</p>
            <p class="text-xs text-gray-500 text-center mt-1">Customer support</p>
        </a>

        <a href="<?= url('admin/settings') ?>" class="group bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 transform hover:-translate-y-1">
            <div class="w-14 h-14 bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-cog text-white text-xl"></i>
            </div>
            <p class="text-sm font-bold text-gray-900 text-center">Settings</p>
            <p class="text-xs text-gray-500 text-center mt-1">System configuration</p>
        </a>

        <a href="<?= url('admin/logout') ?>" class="group bg-white p-6 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 transform hover:-translate-y-1">
            <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mx-auto mb-4 shadow-lg group-hover:scale-110 transition-transform duration-300">
                <i class="fas fa-sign-out-alt text-white text-xl"></i>
            </div>
            <p class="text-sm font-bold text-gray-900 text-center">Logout</p>
            <p class="text-xs text-gray-500 text-center mt-1">Sign out securely</p>
        </a>
    </div>
</div>

<script>
// Modern Revenue Chart with Gradient
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    const revenueData = <?= json_encode($monthly_revenue ?? []) ?>;

    // Create gradient
    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
    gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: revenueData.map(item => item.month_name || item.month),
            datasets: [{
                label: 'Revenue',
                data: revenueData.map(item => parseFloat(item.revenue) || 0),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: gradient,
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: 'rgb(59, 130, 246)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: 'rgb(37, 99, 235)',
                pointHoverBorderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString('en-IN');
                        },
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        color: '#6B7280'
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        color: '#6B7280'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: ₹' + context.parsed.y.toLocaleString('en-IN');
                        }
                    }
                }
            }
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/admin.php';
?>
