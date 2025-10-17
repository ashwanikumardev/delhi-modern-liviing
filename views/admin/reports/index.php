<?php
$page_title = 'Reports & Analytics';
ob_start();
?>

<!-- Modern Reports Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                Reports & Analytics
            </h1>
            <p class="text-gray-600 mt-2">Comprehensive business insights and analytics</p>
        </div>
        <div class="flex gap-3">
            <button onclick="refreshReports()" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 flex items-center gap-2 shadow-lg">
                <i class="fas fa-sync-alt" id="refresh-icon"></i>
                <span class="font-semibold">Refresh</span>
            </button>
            <button onclick="exportReport()" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 flex items-center gap-2 shadow-lg">
                <i class="fas fa-download"></i>
                <span class="font-semibold">Export Report</span>
            </button>
        </div>
    </div>
</div>

<!-- Report Filters -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-100">
    <form method="GET" action="<?= url('admin/reports') ?>" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Report Type</label>
            <select name="report_type" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <option value="overview" <?= ($reportType ?? 'overview') === 'overview' ? 'selected' : '' ?>>Overview</option>
                <option value="revenue" <?= ($reportType ?? '') === 'revenue' ? 'selected' : '' ?>>Revenue</option>
                <option value="bookings" <?= ($reportType ?? '') === 'bookings' ? 'selected' : '' ?>>Bookings</option>
                <option value="users" <?= ($reportType ?? '') === 'users' ? 'selected' : '' ?>>Users</option>
                <option value="rooms" <?= ($reportType ?? '') === 'rooms' ? 'selected' : '' ?>>Rooms</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Date Range</label>
            <select name="date_range" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                <option value="7" <?= ($dateRange ?? '30') == '7' ? 'selected' : '' ?>>Last 7 Days</option>
                <option value="30" <?= ($dateRange ?? '30') == '30' ? 'selected' : '' ?>>Last 30 Days</option>
                <option value="90" <?= ($dateRange ?? '30') == '90' ? 'selected' : '' ?>>Last 90 Days</option>
                <option value="365" <?= ($dateRange ?? '30') == '365' ? 'selected' : '' ?>>Last Year</option>
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl hover:from-purple-700 hover:to-pink-700 transition-all duration-300 font-semibold">
                <i class="fas fa-chart-bar mr-2"></i>Generate Report
            </button>
        </div>
    </form>
</div>

<!-- Overview Report -->
<?php if (($reportType ?? 'overview') === 'overview'): ?>
    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-rupee-sign text-white text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
            <p class="text-3xl font-bold text-gray-900">₹<?= number_format($data['total_revenue'] ?? 0) ?></p>
            <p class="text-sm text-green-600 mt-2">
                <i class="fas fa-arrow-up mr-1"></i>
                <?= number_format($data['revenue_growth'] ?? 0, 1) ?>% from last period
            </p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar-check text-white text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">Total Bookings</p>
            <p class="text-3xl font-bold text-gray-900"><?= number_format($data['total_bookings'] ?? 0) ?></p>
            <p class="text-sm text-blue-600 mt-2">
                <i class="fas fa-arrow-up mr-1"></i>
                <?= number_format($data['bookings_growth'] ?? 0, 1) ?>% from last period
            </p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-users text-white text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">New Users</p>
            <p class="text-3xl font-bold text-gray-900"><?= number_format($data['new_users'] ?? 0) ?></p>
            <p class="text-sm text-purple-600 mt-2">
                <i class="fas fa-arrow-up mr-1"></i>
                <?= number_format($data['users_growth'] ?? 0, 1) ?>% from last period
            </p>
        </div>
        
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
            </div>
            <p class="text-sm text-gray-600 mb-1">Avg. Booking Value</p>
            <p class="text-3xl font-bold text-gray-900">₹<?= number_format($data['avg_booking_value'] ?? 0) ?></p>
            <p class="text-sm text-yellow-600 mt-2">
                <i class="fas fa-arrow-up mr-1"></i>
                <?= number_format($data['avg_value_growth'] ?? 0, 1) ?>% from last period
            </p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Trend -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-chart-line text-green-600 mr-2"></i>
                Revenue Trend
            </h3>
            <div class="h-64">
                <canvas id="revenueTrendChart"></canvas>
            </div>
        </div>
        
        <!-- Bookings Trend -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                Bookings Trend
            </h3>
            <div class="h-64">
                <canvas id="bookingsTrendChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Top Performers -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Rooms -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                Top Performing Rooms
            </h3>
            <div class="space-y-4">
                <?php if (!empty($data['top_rooms'])): ?>
                    <?php foreach ($data['top_rooms'] as $index => $room): ?>
                        <div class="flex items-center gap-4">
                            <div class="w-8 h-8 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg flex items-center justify-center text-white font-bold">
                                <?= $index + 1 ?>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900"><?= htmlspecialchars($room['title'] ?? 'N/A') ?></p>
                                <p class="text-sm text-gray-500"><?= $room['bookings'] ?? 0 ?> bookings • ₹<?= number_format($room['revenue'] ?? 0) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-gray-500 text-center py-8">No data available</p>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Payment Methods -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h3 class="text-lg font-bold text-gray-900 mb-4">
                <i class="fas fa-credit-card text-purple-600 mr-2"></i>
                Payment Methods
            </h3>
            <div class="h-64">
                <canvas id="paymentMethodsChart"></canvas>
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Revenue Report -->
<?php if (($reportType ?? '') === 'revenue'): ?>
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Revenue Report</h3>
        <div class="h-96">
            <canvas id="revenueDetailChart"></canvas>
        </div>
    </div>
<?php endif; ?>

<!-- Bookings Report -->
<?php if (($reportType ?? '') === 'bookings'): ?>
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Bookings Report</h3>
        <div class="h-96">
            <canvas id="bookingsDetailChart"></canvas>
        </div>
    </div>
<?php endif; ?>

<!-- Users Report -->
<?php if (($reportType ?? '') === 'users'): ?>
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Users Report</h3>
        <div class="h-96">
            <canvas id="usersDetailChart"></canvas>
        </div>
    </div>
<?php endif; ?>

<!-- Rooms Report -->
<?php if (($reportType ?? '') === 'rooms'): ?>
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <h3 class="text-xl font-bold text-gray-900 mb-6">Rooms Report</h3>
        <div class="h-96">
            <canvas id="roomsDetailChart"></canvas>
        </div>
    </div>
<?php endif; ?>

<script>
function exportReport() {
    // Get current filter values
    const reportType = document.querySelector('select[name="report_type"]').value;
    const dateRange = document.querySelector('select[name="date_range"]').value;

    // Show export options
    const format = prompt('Choose export format:\n1. CSV\n2. PDF\n\nEnter 1 or 2:', '1');

    if (format === '1' || format === 'csv') {
        const url = `<?= url('admin/reports/export') ?>?format=csv&report_type=${reportType}&date_range=${dateRange}`;
        window.open(url, '_blank');
    } else if (format === '2' || format === 'pdf') {
        const url = `<?= url('admin/reports/export') ?>?format=pdf&report_type=${reportType}&date_range=${dateRange}`;
        window.open(url, '_blank');
    }
}

function refreshReports() {
    const refreshIcon = document.getElementById('refresh-icon');
    refreshIcon.classList.add('fa-spin');

    // Get current filter values
    const reportType = document.querySelector('select[name="report_type"]').value;
    const dateRange = document.querySelector('select[name="date_range"]').value;

    // Reload page with current filters
    const url = `<?= url('admin/reports') ?>?report_type=${reportType}&date_range=${dateRange}`;
    window.location.href = url;
}

// Auto-refresh every 5 minutes
let autoRefreshInterval;

function startAutoRefresh() {
    autoRefreshInterval = setInterval(() => {
        refreshReports();
    }, 300000); // 5 minutes
}

function stopAutoRefresh() {
    if (autoRefreshInterval) {
        clearInterval(autoRefreshInterval);
    }
}

// Start auto-refresh when page loads
document.addEventListener('DOMContentLoaded', function() {
    startAutoRefresh();

    // Add real-time timestamp
    const timestamp = document.createElement('div');
    timestamp.className = 'text-sm text-gray-500 mt-2';
    timestamp.innerHTML = `<i class="fas fa-clock mr-1"></i>Last updated: ${new Date().toLocaleString()}`;
    document.querySelector('.mb-8').appendChild(timestamp);

    // Update timestamp every minute
    setInterval(() => {
        timestamp.innerHTML = `<i class="fas fa-clock mr-1"></i>Last updated: ${new Date().toLocaleString()}`;
    }, 60000);
});

// Stop auto-refresh when user leaves page
window.addEventListener('beforeunload', stopAutoRefresh);

// Initialize charts if Chart.js is loaded
if (typeof Chart !== 'undefined') {
    // Revenue Trend Chart
    const revenueTrendCtx = document.getElementById('revenueTrendChart');
    if (revenueTrendCtx) {
        new Chart(revenueTrendCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Revenue',
                    data: [45000, 52000, 48000, 65000],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    
    // Bookings Trend Chart
    const bookingsTrendCtx = document.getElementById('bookingsTrendChart');
    if (bookingsTrendCtx) {
        new Chart(bookingsTrendCtx, {
            type: 'bar',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Bookings',
                    data: [12, 15, 13, 18],
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
    
    // Payment Methods Chart
    const paymentMethodsCtx = document.getElementById('paymentMethodsChart');
    if (paymentMethodsCtx) {
        new Chart(paymentMethodsCtx, {
            type: 'doughnut',
            data: {
                labels: ['Online', 'Cash', 'Card'],
                datasets: [{
                    data: [60, 25, 15],
                    backgroundColor: [
                        'rgba(147, 51, 234, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(34, 197, 94, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
}
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/admin.php';
?>

