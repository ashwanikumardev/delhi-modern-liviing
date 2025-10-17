<?php
$page_title = 'Order Details - #' . $order['id'];
ob_start();
?>

<!-- Modern Order Details Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                Order #<?= $order['id'] ?>
            </h1>
            <p class="text-gray-600 mt-2">Complete order details and management</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= url('admin/orders') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span class="font-semibold">Back to Orders</span>
            </a>
            <button onclick="printOrder()" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-print"></i>
                <span class="font-semibold">Print</span>
            </button>
        </div>
    </div>
</div>

<!-- Order Status Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Order Status</p>
                <span class="px-3 py-1 rounded-full text-sm font-bold 
                    <?= $order['booking_status'] === 'confirmed' ? 'bg-green-100 text-green-700' : 
                        ($order['booking_status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                        ($order['booking_status'] === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700')) ?>">
                    <?= ucfirst($order['booking_status']) ?>
                </span>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-clipboard-list text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Payment Status</p>
                <span class="px-3 py-1 rounded-full text-sm font-bold 
                    <?= $order['payment_status'] === 'paid' ? 'bg-green-100 text-green-700' : 
                        ($order['payment_status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 
                        ($order['payment_status'] === 'failed' ? 'bg-red-100 text-red-700' : 'bg-purple-100 text-purple-700')) ?>">
                    <?= ucfirst($order['payment_status']) ?>
                </span>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-credit-card text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Amount</p>
                <p class="text-2xl font-bold text-gray-900">₹<?= number_format($order['total_amount']) ?></p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-rupee-sign text-white"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Duration</p>
                <p class="text-2xl font-bold text-gray-900"><?= $order['months_count'] ?> Month(s)</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar text-white"></i>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Customer Information -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-user text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Customer Information</h3>
                <p class="text-sm text-gray-600">Customer details and contact information</p>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="font-semibold text-gray-700">Name:</span>
                <span class="text-gray-900"><?= htmlspecialchars($order['user_name']) ?></span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="font-semibold text-gray-700">Email:</span>
                <span class="text-gray-900"><?= htmlspecialchars($order['user_email']) ?></span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="font-semibold text-gray-700">Phone:</span>
                <span class="text-gray-900"><?= htmlspecialchars($order['user_phone'] ?? 'N/A') ?></span>
            </div>
        </div>
    </div>
    
    <!-- Room Information -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-home text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Room Information</h3>
                <p class="text-sm text-gray-600">Room details and location</p>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="font-semibold text-gray-700">Room Title:</span>
                <span class="text-gray-900"><?= htmlspecialchars($order['room_title']) ?></span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="font-semibold text-gray-700">Location:</span>
                <span class="text-gray-900"><?= htmlspecialchars($order['room_address']) ?></span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="font-semibold text-gray-700">City:</span>
                <span class="text-gray-900"><?= htmlspecialchars($order['room_city']) ?></span>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details -->
<div class="mt-8 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
            <i class="fas fa-calendar-alt text-white text-xl"></i>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-900">Booking Details</h3>
            <p class="text-sm text-gray-600">Booking period and dates</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="p-4 bg-blue-50 rounded-xl">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-calendar-plus text-blue-600"></i>
                <span class="font-semibold text-gray-700">Start Date</span>
            </div>
            <p class="text-lg font-bold text-gray-900"><?= date('M d, Y', strtotime($order['start_date'])) ?></p>
            <p class="text-sm text-gray-600"><?= date('l', strtotime($order['start_date'])) ?></p>
        </div>
        
        <div class="p-4 bg-red-50 rounded-xl">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-calendar-minus text-red-600"></i>
                <span class="font-semibold text-gray-700">End Date</span>
            </div>
            <p class="text-lg font-bold text-gray-900"><?= date('M d, Y', strtotime($order['end_date'])) ?></p>
            <p class="text-sm text-gray-600"><?= date('l', strtotime($order['end_date'])) ?></p>
        </div>
        
        <div class="p-4 bg-green-50 rounded-xl">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-clock text-green-600"></i>
                <span class="font-semibold text-gray-700">Duration</span>
            </div>
            <p class="text-lg font-bold text-gray-900"><?= $order['months_count'] ?> Month(s)</p>
        </div>
        
        <div class="p-4 bg-yellow-50 rounded-xl">
            <div class="flex items-center gap-2 mb-2">
                <i class="fas fa-calendar-check text-yellow-600"></i>
                <span class="font-semibold text-gray-700">Order Date</span>
            </div>
            <p class="text-lg font-bold text-gray-900"><?= date('M d, Y', strtotime($order['created_at'])) ?></p>
            <p class="text-sm text-gray-600"><?= date('H:i A', strtotime($order['created_at'])) ?></p>
        </div>
    </div>
</div>

<!-- Payment Details -->
<div class="mt-8 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center">
            <i class="fas fa-receipt text-white text-xl"></i>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-900">Payment Breakdown</h3>
            <p class="text-sm text-gray-600">Detailed payment information</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="space-y-4">
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-700">Monthly Rent:</span>
                <span class="font-semibold text-gray-900">₹<?= number_format($order['monthly_rent']) ?></span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-700">Duration:</span>
                <span class="font-semibold text-gray-900"><?= $order['months_count'] ?> month(s)</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-700">Rent Total:</span>
                <span class="font-semibold text-gray-900">₹<?= number_format($order['monthly_rent'] * $order['months_count']) ?></span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-700">Security Deposit:</span>
                <span class="font-semibold text-gray-900">₹<?= number_format($order['deposit_amount']) ?></span>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-700">GST Amount:</span>
                <span class="font-semibold text-gray-900">₹<?= number_format($order['gst_amount'] ?? 0) ?></span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-700">Discount:</span>
                <span class="font-semibold text-red-600">-₹<?= number_format($order['discount_amount'] ?? 0) ?></span>
            </div>
            <?php if (!empty($order['coupon_code'])): ?>
            <div class="flex justify-between items-center py-3 border-b border-gray-100">
                <span class="text-gray-700">Coupon Code:</span>
                <span class="font-semibold text-blue-600"><?= htmlspecialchars($order['coupon_code']) ?></span>
            </div>
            <?php endif; ?>
            <div class="flex justify-between items-center py-3 border-t-2 border-gray-300">
                <span class="text-lg font-bold text-gray-900">Total Amount:</span>
                <span class="text-xl font-bold text-green-600">₹<?= number_format($order['total_amount']) ?></span>
            </div>
        </div>
    </div>
    
    <!-- Payment Method & Transaction Details -->
    <?php if (!empty($order['payment_method']) || !empty($order['transaction_id'])): ?>
    <div class="mt-6 pt-6 border-t border-gray-200">
        <h4 class="font-semibold text-gray-900 mb-4">Transaction Details</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php if (!empty($order['payment_method'])): ?>
            <div class="flex justify-between items-center py-2">
                <span class="text-gray-700">Payment Method:</span>
                <span class="font-semibold text-gray-900"><?= ucfirst(str_replace('_', ' ', $order['payment_method'])) ?></span>
            </div>
            <?php endif; ?>
            <?php if (!empty($order['transaction_id'])): ?>
            <div class="flex justify-between items-center py-2">
                <span class="text-gray-700">Transaction ID:</span>
                <span class="font-semibold text-gray-900"><?= htmlspecialchars($order['transaction_id']) ?></span>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Action Buttons -->
<div class="mt-8 flex justify-end gap-4">
    <button onclick="updateOrderStatus()" class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-all duration-300">
        <i class="fas fa-edit mr-2"></i>Update Status
    </button>
    <button onclick="deleteOrder()" class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-all duration-300">
        <i class="fas fa-trash mr-2"></i>Delete Order
    </button>
</div>

<script>
function printOrder() {
    window.print();
}

function updateOrderStatus() {
    // Implementation for status update modal
    alert('Status update functionality for Order #<?= $order['id'] ?>');
}

function deleteOrder() {
    if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
        const formData = new FormData();
        formData.append('csrf_token', '<?= $csrf_token ?? '' ?>');
        
        fetch('<?= url('admin/orders/' . $order['id'] . '/delete') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '<?= url('admin/orders') ?>';
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
