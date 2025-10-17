<?php
$page_title = 'Order Management';
ob_start();
?>

<!-- Modern Order Management Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                Order Management
            </h1>
            <p class="text-gray-600 mt-2">Manage all orders and transactions</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= url('admin/orders/create') ?>" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 flex items-center gap-2 shadow-lg">
                <i class="fas fa-plus"></i>
                <span class="font-semibold">Create Order</span>
            </a>
            <button onclick="exportOrders()" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 flex items-center gap-2 shadow-lg">
                <i class="fas fa-download"></i>
                <span class="font-semibold">Export</span>
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-6 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Orders</p>
                <p class="text-3xl font-bold text-gray-900"><?= number_format($stats['total'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-shopping-cart text-white text-xl"></i>
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
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Paid Orders</p>
                <p class="text-3xl font-bold text-purple-600"><?= number_format($stats['paid'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-credit-card text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Revenue</p>
                <p class="text-2xl font-bold text-green-600">₹<?= number_format($stats['revenue'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-rupee-sign text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-8 border border-gray-100">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div>
            <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
            <input type="text" id="search" name="search" 
                   value="<?= htmlspecialchars($search ?? '') ?>"
                   placeholder="Order ID, user name, email, room..."
                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
        </div>
        
        <div>
            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Booking Status</label>
            <select id="status" name="status" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                <option value="">All Status</option>
                <option value="confirmed" <?= ($status ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="pending" <?= ($status ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="cancelled" <?= ($status ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                <option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
            </select>
        </div>
        
        <div>
            <label for="payment_status" class="block text-sm font-semibold text-gray-700 mb-2">Payment Status</label>
            <select id="payment_status" name="payment_status" 
                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                <option value="">All Payment Status</option>
                <option value="pending" <?= ($payment_status ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="paid" <?= ($payment_status ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="failed" <?= ($payment_status ?? '') === 'failed' ? 'selected' : '' ?>>Failed</option>
                <option value="refunded" <?= ($payment_status ?? '') === 'refunded' ? 'selected' : '' ?>>Refunded</option>
            </select>
        </div>
        
        <div class="flex items-end">
            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-300 font-semibold">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
        </div>
    </form>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-900">Orders List</h3>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Order</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Room</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Dates</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Payment</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($orders)): ?>
                    <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">#<?= $order['id'] ?></p>
                                <p class="text-xs text-gray-500"><?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900"><?= htmlspecialchars($order['user_name']) ?></p>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars($order['user_email']) ?></p>
                                <p class="text-xs text-gray-400"><?= htmlspecialchars($order['user_phone'] ?? 'N/A') ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900"><?= htmlspecialchars($order['room_title']) ?></p>
                                <p class="text-xs text-gray-500"><?= htmlspecialchars($order['room_city']) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900">
                                    <?= isset($order['start_date']) && $order['start_date'] ? date('M d, Y', strtotime($order['start_date'])) : 'N/A' ?>
                                </p>
                                <p class="text-xs text-gray-500">
                                    to <?= isset($order['end_date']) && $order['end_date'] ? date('M d, Y', strtotime($order['end_date'])) : 'N/A' ?>
                                </p>
                                <p class="text-xs text-gray-400"><?= $order['months_count'] ?> month(s)</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">₹<?= number_format($order['total_amount']) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    <?= $order['booking_status'] === 'confirmed' ? 'bg-green-100 text-green-700' : 
                                        ($order['booking_status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                                        ($order['booking_status'] === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700')) ?>">
                                    <?= ucfirst($order['booking_status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    <?= $order['payment_status'] === 'paid' ? 'bg-green-100 text-green-700' : 
                                        ($order['payment_status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                                        ($order['payment_status'] === 'failed' ? 'bg-red-100 text-red-700' : 'bg-purple-100 text-purple-700')) ?>">
                                    <?= ucfirst($order['payment_status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="<?= url('admin/orders/' . $order['id']) ?>" 
                                       class="text-blue-600 hover:text-blue-900 font-medium">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button onclick="updateOrderStatus(<?= $order['id'] ?>)" 
                                            class="text-green-600 hover:text-green-900 font-medium">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteOrder(<?= $order['id'] ?>)" 
                                            class="text-red-600 hover:text-red-900 font-medium">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-shopping-cart text-gray-400 text-4xl mb-4"></i>
                                <p class="text-gray-500 text-lg font-medium">No orders found</p>
                                <p class="text-gray-400 text-sm">Create your first order to get started</p>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Page <?= $currentPage ?> of <?= $totalPages ?>
                </div>
                <div class="flex space-x-2">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?= $currentPage - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>&payment_status=<?= urlencode($payment_status) ?>" 
                           class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Previous
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?= $currentPage + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>&payment_status=<?= urlencode($payment_status) ?>" 
                           class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Next
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function exportOrders() {
    window.location.href = '<?= url('admin/orders/export') ?>?format=csv';
}

function updateOrderStatus(orderId) {
    // Implementation for status update modal
    alert('Status update functionality - Order ID: ' + orderId);
}

function deleteOrder(orderId) {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        const formData = new FormData();
        formData.append('csrf_token', '<?= $csrf_token ?? '' ?>');
        
        fetch('<?= url('admin/orders/') ?>' + orderId + '/delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting order: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting order');
        });
    }
}
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/admin.php';
?>
