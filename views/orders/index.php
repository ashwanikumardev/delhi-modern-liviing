<?php
$title = 'My Bookings - Delhi Modern Living';
ob_start();
?>

<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Bookings</h1>
            <p class="text-gray-600 mt-2">View and manage your room bookings</p>
        </div>
        
        <?php if (empty($orders)): ?>
            <!-- No Orders -->
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="text-6xl text-gray-300 mb-4">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">No bookings yet</h2>
                <p class="text-gray-600 mb-6">Start browsing our rooms to make your first booking</p>
                <a href="<?= url('rooms') ?>" class="bg-primary-600 text-white hover:bg-primary-700 px-6 py-3 rounded-lg font-semibold transition duration-300">
                    Browse Rooms
                </a>
            </div>
        <?php else: ?>
            <!-- Orders List -->
            <div class="space-y-6">
                <?php foreach ($orders as $order): 
                    $images = json_decode($order['room_images'], true) ?? [];
                    $statusColors = [
                        'confirmed' => 'bg-green-100 text-green-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                        'completed' => 'bg-blue-100 text-blue-800'
                    ];
                    $paymentColors = [
                        'paid' => 'bg-green-100 text-green-800',
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'failed' => 'bg-red-100 text-red-800',
                        'refunded' => 'bg-gray-100 text-gray-800'
                    ];
                ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start space-x-4">
                                    <!-- Room Image -->
                                    <div class="flex-shrink-0">
                                        <img src="/assets/images/rooms/<?= $images[0] ?? 'placeholder.jpg' ?>" 
                                             alt="<?= htmlspecialchars($order['room_title']) ?>"
                                             class="w-20 h-20 object-cover rounded-lg"
                                             onerror="this.src='/assets/images/placeholder-room.jpg'">
                                    </div>
                                    
                                    <!-- Order Details -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                            <?= htmlspecialchars($order['room_title']) ?>
                                        </h3>
                                        <p class="text-gray-600 text-sm mb-2 flex items-center">
                                            <i class="fas fa-map-marker-alt text-primary-600 mr-1"></i>
                                            <?= htmlspecialchars($order['room_address']) ?>
                                        </p>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                            <div>
                                                <span class="text-gray-500">Check-in:</span>
                                                <span class="font-medium ml-1"><?= date('M d, Y', strtotime($order['start_date'])) ?></span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Check-out:</span>
                                                <span class="font-medium ml-1"><?= date('M d, Y', strtotime($order['end_date'])) ?></span>
                                            </div>
                                            <div>
                                                <span class="text-gray-500">Duration:</span>
                                                <span class="font-medium ml-1"><?= $order['months_count'] ?> months</span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-3 flex items-center space-x-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $statusColors[$order['booking_status']] ?? 'bg-gray-100 text-gray-800' ?>">
                                                <?= ucfirst($order['booking_status']) ?>
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $paymentColors[$order['payment_status']] ?? 'bg-gray-100 text-gray-800' ?>">
                                                Payment: <?= ucfirst($order['payment_status']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Order Summary -->
                                <div class="text-right">
                                    <div class="text-sm text-gray-500 mb-1">Order #<?= $order['id'] ?></div>
                                    <div class="text-lg font-semibold text-primary-600 mb-2">
                                        ₹<?= number_format($order['total_amount']) ?>
                                    </div>
                                    <div class="text-xs text-gray-500 mb-3">
                                        Booked on <?= date('M d, Y', strtotime($order['created_at'])) ?>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <a href="<?= url('orders/' . $order['id']) ?>" 
                                           class="block bg-primary-600 text-white hover:bg-primary-700 px-4 py-2 rounded-md text-sm font-medium text-center transition duration-300">
                                            View Details
                                        </a>
                                        
                                        <?php if ($order['booking_status'] === 'confirmed' && $order['payment_status'] === 'paid'): ?>
                                            <button onclick="downloadInvoice(<?= $order['id'] ?>)" 
                                                    class="block w-full bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-md text-sm font-medium text-center transition duration-300">
                                                <i class="fas fa-download mr-1"></i>
                                                Invoice
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if ($order['booking_status'] === 'confirmed' && strtotime($order['start_date']) > time()): ?>
                                            <button onclick="cancelBooking(<?= $order['id'] ?>)" 
                                                    class="block w-full bg-red-100 text-red-700 hover:bg-red-200 px-4 py-2 rounded-md text-sm font-medium text-center transition duration-300">
                                                <i class="fas fa-times mr-1"></i>
                                                Cancel
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar for Active Bookings -->
                        <?php if ($order['booking_status'] === 'confirmed' && strtotime($order['start_date']) <= time() && strtotime($order['end_date']) >= time()): ?>
                            <?php
                                $totalDays = (strtotime($order['end_date']) - strtotime($order['start_date'])) / (60 * 60 * 24);
                                $elapsedDays = (time() - strtotime($order['start_date'])) / (60 * 60 * 24);
                                $progress = min(100, max(0, ($elapsedDays / $totalDays) * 100));
                            ?>
                            <div class="px-6 pb-4">
                                <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                    <div class="flex items-center justify-between text-sm text-green-800 mb-2">
                                        <span><i class="fas fa-home mr-1"></i>Currently staying</span>
                                        <span><?= round($totalDays - $elapsedDays) ?> days remaining</span>
                                    </div>
                                    <div class="w-full bg-green-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300" style="width: <?= $progress ?>%"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($pagination['last_page'] > 1): ?>
                <div class="mt-8 flex justify-center">
                    <nav class="flex items-center space-x-2">
                        <?php if ($pagination['current_page'] > 1): ?>
                            <a href="?page=<?= $pagination['current_page'] - 1 ?>" 
                               class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Previous
                            </a>
                        <?php endif; ?>
                        
                        <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['last_page'], $pagination['current_page'] + 2); $i++): ?>
                            <a href="?page=<?= $i ?>" 
                               class="px-3 py-2 text-sm font-medium <?= $i === $pagination['current_page'] ? 'text-white bg-primary-600 border-primary-600' : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50' ?> border rounded-md">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                        
                        <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                            <a href="?page=<?= $pagination['current_page'] + 1 ?>" 
                               class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                Next
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<script>
function downloadInvoice(orderId) {
    // In a real implementation, this would generate and download the invoice
    Utils.showToast('Invoice download functionality will be implemented soon', 'info');
}

function cancelBooking(orderId) {
    if (confirm('Are you sure you want to cancel this booking? Cancellation charges may apply.')) {
        // In a real implementation, this would handle booking cancellation
        Utils.showToast('Booking cancellation functionality will be implemented soon', 'info');
    }
}
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/app.php';
?>
