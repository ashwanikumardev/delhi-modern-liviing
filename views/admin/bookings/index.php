<?php
$page_title = 'Booking Management';
ob_start();
?>

<!-- Modern Booking Management Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-transparent">
                Booking Management
            </h1>
            <p class="text-gray-600 mt-2">Manage all bookings and reservations</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= url('admin/bookings/create') ?>" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 flex items-center gap-2 shadow-lg">
                <i class="fas fa-plus"></i>
                <span class="font-semibold">Add Booking</span>
            </a>
            <button onclick="exportBookings()" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 flex items-center gap-2 shadow-lg">
                <i class="fas fa-download"></i>
                <span class="font-semibold">Export</span>
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Bookings</p>
                <p class="text-3xl font-bold text-gray-900"><?= number_format($stats['total'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-calendar-alt text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Confirmed</p>
                <p class="text-3xl font-bold text-green-600"><?= number_format($stats['confirmed'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-check-circle text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Pending</p>
                <p class="text-3xl font-bold text-yellow-600"><?= number_format($stats['pending'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-clock text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Cancelled</p>
                <p class="text-3xl font-bold text-red-600"><?= number_format($stats['cancelled'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-times-circle text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-100">
    <form method="GET" action="<?= url('admin/bookings') ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" 
                   placeholder="Search bookings..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Booking Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="confirmed" <?= ($filters['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="cancelled" <?= ($filters['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Status</label>
            <select name="payment_status" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                <option value="">All Payment Status</option>
                <option value="paid" <?= ($filters['payment_status'] ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="pending" <?= ($filters['payment_status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="failed" <?= ($filters['payment_status'] ?? '') === 'failed' ? 'selected' : '' ?>>Failed</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-yellow-600 to-orange-600 text-white rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all duration-300 font-semibold">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="<?= url('admin/bookings') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Bookings Table -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-yellow-50 to-orange-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Booking ID</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Room</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Check-in</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Booking Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Payment</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($bookings)): ?>
                    <?php foreach ($bookings as $booking): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">#<?= $booking['id'] ?></p>
                                <p class="text-xs text-gray-500"><?= date('M d, Y', strtotime($booking['created_at'])) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900"><?= htmlspecialchars($booking['user_name']) ?></p>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars($booking['user_email']) ?></p>
                                <p class="text-xs text-gray-400"><?= htmlspecialchars($booking['user_phone'] ?? 'N/A') ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900"><?= htmlspecialchars($booking['room_title']) ?></p>
                                <p class="text-xs text-gray-500"><?= htmlspecialchars($booking['room_city']) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">
                                    <?= isset($booking['start_date']) && $booking['start_date'] ? date('M d, Y', strtotime($booking['start_date'])) : 'N/A' ?>
                                </p>
                                <p class="text-xs text-gray-500">
                                    to <?= isset($booking['end_date']) && $booking['end_date'] ? date('M d, Y', strtotime($booking['end_date'])) : 'N/A' ?>
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">₹<?= number_format($booking['total_amount']) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    <?= $booking['booking_status'] === 'confirmed' ? 'bg-green-100 text-green-700' : 
                                        ($booking['booking_status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') ?>">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    <?= ucfirst($booking['booking_status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    <?= $booking['payment_status'] === 'paid' ? 'bg-green-100 text-green-700' : 
                                        ($booking['payment_status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') ?>">
                                    <?= ucfirst($booking['payment_status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="<?= url('admin/bookings/' . $booking['id'] . '/view') ?>" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-semibold">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($booking['booking_status'] === 'pending'): ?>
                                        <button onclick="approveBooking(<?= $booking['id'] ?>)" class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors text-sm font-semibold">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <button onclick="rejectBooking(<?= $booking['id'] ?>)" class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-semibold">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg">No bookings found</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Showing page <?= $currentPage ?> of <?= $totalPages ?>
                </p>
                <div class="flex gap-2">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= url('admin/bookings?page=' . ($currentPage - 1)) ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                        <a href="<?= url('admin/bookings?page=' . $i) ?>" class="px-4 py-2 <?= $i === $currentPage ? 'bg-yellow-600 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50' ?> rounded-lg transition-colors">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= url('admin/bookings?page=' . ($currentPage + 1)) ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function approveBooking(id) {
    if (confirm('Are you sure you want to approve this booking?')) {
        fetch('<?= url('admin/bookings/') ?>' + id + '/approve', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error approving booking');
            }
        });
    }
}

function rejectBooking(id) {
    if (confirm('Are you sure you want to reject this booking?')) {
        fetch('<?= url('admin/bookings/') ?>' + id + '/reject', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error rejecting booking');
            }
        });
    }
}

function exportBookings() {
    // Get current filter values
    const search = document.querySelector('input[name="search"]').value;
    const status = document.querySelector('select[name="status"]').value;
    const paymentStatus = document.querySelector('select[name="payment_status"]').value;

    // Show export options
    const format = prompt('Choose export format:\n1. CSV\n2. PDF\n\nEnter 1 or 2:', '1');

    if (format === '1' || format === 'csv') {
        const url = `<?= url('admin/bookings/export') ?>?format=csv&search=${search}&status=${status}&payment_status=${paymentStatus}`;
        window.open(url, '_blank');
    } else if (format === '2' || format === 'pdf') {
        const url = `<?= url('admin/bookings/export') ?>?format=pdf&search=${search}&status=${status}&payment_status=${paymentStatus}`;
        window.open(url, '_blank');
    }
}
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/admin.php';
?>

