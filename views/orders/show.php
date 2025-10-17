<?php
$page_title = 'Order Details';
ob_start();
?>

<!-- Modern Order Details Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                Order #<?= $order['id'] ?>
            </h1>
            <p class="text-gray-600 mt-2">Order placed on <?= date('M d, Y \a\t g:i A', strtotime($order['created_at'])) ?></p>
        </div>
        <div class="flex gap-3">
            <a href="<?= url('orders/' . $order['id'] . '/invoice') ?>" target="_blank" class="px-6 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 flex items-center gap-2 shadow-lg">
                <i class="fas fa-file-invoice"></i>
                <span class="font-semibold">Download Invoice</span>
            </a>
            <a href="<?= url('orders') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span class="font-semibold">Back to Orders</span>
            </a>
        </div>
    </div>
</div>

<!-- Order Status Banner -->
<div class="mb-8">
    <div class="bg-gradient-to-r <?= $order['status'] === 'confirmed' ? 'from-green-500 to-green-600' : 
        ($order['status'] === 'pending' ? 'from-yellow-500 to-yellow-600' : 'from-red-500 to-red-600') ?> 
        rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-<?= $order['status'] === 'confirmed' ? 'check-circle' : 
                        ($order['status'] === 'pending' ? 'clock' : 'times-circle') ?> text-3xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold">Order <?= ucfirst($order['status']) ?></h2>
                    <p class="text-white/80">
                        <?= $order['status'] === 'confirmed' ? 'Your booking has been confirmed!' : 
                            ($order['status'] === 'pending' ? 'Your booking is being processed' : 'Booking was cancelled') ?>
                    </p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-white/80 text-sm">Total Amount</p>
                <p class="text-3xl font-bold">₹<?= number_format($order['total_amount']) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Order Information Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Booking Details -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-calendar-check text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Booking Details</h3>
                <p class="text-sm text-gray-600">Your reservation information</p>
            </div>
        </div>
        
        <div class="space-y-6">
            <!-- Room Information -->
            <div class="p-4 bg-gray-50 rounded-xl">
                <div class="flex items-start gap-4">
                    <?php
                    require_once 'helpers/ImageHelper.php';
                    $roomImages = json_decode($order['room_images'] ?? '[]', true);
                    ?>
                    <?php if (!empty($roomImages)): ?>
                        <img src="<?= ImageHelper::getImageUrl($roomImages[0]) ?>" alt="Room" class="w-20 h-20 rounded-lg object-cover">
                    <?php else: ?>
                        <img src="<?= url('assets/images/placeholder-room.jpg') ?>" alt="Room" class="w-20 h-20 rounded-lg object-cover">
                    <?php endif; ?>
                    <div class="flex-1">
                        <h4 class="text-lg font-bold text-gray-900"><?= htmlspecialchars($room['title']) ?></h4>
                        <p class="text-gray-600"><?= htmlspecialchars($room['location']) ?></p>
                        <div class="flex items-center gap-4 mt-2">
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold">
                                <?= ucfirst($room['category']) ?>
                            </span>
                            <span class="text-sm text-gray-600">
                                <i class="fas fa-users mr-1"></i>
                                Capacity: <?= $room['capacity'] ?> person(s)
                            </span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-gray-900">₹<?= number_format($room['price']) ?></p>
                        <p class="text-sm text-gray-600">per month</p>
                    </div>
                </div>
            </div>
            
            <!-- Booking Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 border border-gray-200 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-calendar-plus text-green-600"></i>
                        <span class="font-semibold text-gray-700">Check-in Date</span>
                    </div>
                    <p class="text-lg font-bold text-gray-900"><?= date('M d, Y', strtotime($order['start_date'])) ?></p>
                    <p class="text-sm text-gray-600"><?= date('l', strtotime($order['start_date'])) ?></p>
                </div>
                
                <div class="p-4 border border-gray-200 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-calendar-minus text-red-600"></i>
                        <span class="font-semibold text-gray-700">Check-out Date</span>
                    </div>
                    <p class="text-lg font-bold text-gray-900"><?= date('M d, Y', strtotime($order['end_date'])) ?></p>
                    <p class="text-sm text-gray-600"><?= date('l', strtotime($order['end_date'])) ?></p>
                </div>
            </div>
            
            <!-- Duration & Guests -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 border border-gray-200 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-clock text-blue-600"></i>
                        <span class="font-semibold text-gray-700">Duration</span>
                    </div>
                    <?php 
                    $duration = ceil((strtotime($order['end_date']) - strtotime($order['start_date'])) / (30 * 24 * 60 * 60));
                    ?>
                    <p class="text-lg font-bold text-gray-900"><?= $duration ?> month(s)</p>
                </div>
                
                <div class="p-4 border border-gray-200 rounded-xl">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-users text-purple-600"></i>
                        <span class="font-semibold text-gray-700">Guests</span>
                    </div>
                    <p class="text-lg font-bold text-gray-900"><?= $order['guests'] ?> person(s)</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Payment Summary -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-rupee-sign text-white text-xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900">Payment Summary</h3>
                <p class="text-sm text-gray-600">Billing breakdown</p>
            </div>
        </div>
        
        <div class="space-y-4">
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-700">Room Rent</span>
                <span class="font-semibold text-gray-900">₹<?= number_format($order['room_amount']) ?></span>
            </div>
            
            <?php if ($order['security_deposit'] > 0): ?>
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-700">Security Deposit</span>
                <span class="font-semibold text-gray-900">₹<?= number_format($order['security_deposit']) ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($order['service_fee'] > 0): ?>
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-700">Service Fee</span>
                <span class="font-semibold text-gray-900">₹<?= number_format($order['service_fee']) ?></span>
            </div>
            <?php endif; ?>
            
            <?php if ($order['taxes'] > 0): ?>
            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-700">Taxes & Fees</span>
                <span class="font-semibold text-gray-900">₹<?= number_format($order['taxes']) ?></span>
            </div>
            <?php endif; ?>
            
            <div class="flex justify-between items-center py-3 border-t-2 border-gray-200">
                <span class="text-lg font-bold text-gray-900">Total Amount</span>
                <span class="text-xl font-bold text-green-600">₹<?= number_format($order['total_amount']) ?></span>
            </div>
        </div>
        
        <!-- Payment Status -->
        <div class="mt-6 p-4 <?= $order['payment_status'] === 'paid' ? 'bg-green-50 border border-green-200' : 
            ($order['payment_status'] === 'pending' ? 'bg-yellow-50 border border-yellow-200' : 'bg-red-50 border border-red-200') ?> rounded-xl">
            <div class="flex items-center gap-2">
                <i class="fas fa-<?= $order['payment_status'] === 'paid' ? 'check-circle text-green-600' : 
                    ($order['payment_status'] === 'pending' ? 'clock text-yellow-600' : 'times-circle text-red-600') ?>"></i>
                <span class="font-semibold <?= $order['payment_status'] === 'paid' ? 'text-green-700' : 
                    ($order['payment_status'] === 'pending' ? 'text-yellow-700' : 'text-red-700') ?>">
                    Payment <?= ucfirst($order['payment_status']) ?>
                </span>
            </div>
            <?php if (!empty($order['payment_method'])): ?>
                <p class="text-sm text-gray-600 mt-1">via <?= ucfirst($order['payment_method']) ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Customer Information -->
<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-8">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
            <i class="fas fa-user text-white text-xl"></i>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-900">Customer Information</h3>
            <p class="text-sm text-gray-600">Booking contact details</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
    </div>
</div>

<!-- Special Requests -->
<?php if (!empty($order['special_requests'])): ?>
<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100 mb-8">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
            <i class="fas fa-comment-alt text-white text-xl"></i>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-900">Special Requests</h3>
            <p class="text-sm text-gray-600">Additional notes from customer</p>
        </div>
    </div>
    
    <div class="p-4 bg-gray-50 rounded-xl">
        <p class="text-gray-700"><?= nl2br(htmlspecialchars($order['special_requests'])) ?></p>
    </div>
</div>
<?php endif; ?>

<!-- Order Timeline -->
<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
    <div class="flex items-center gap-3 mb-6">
        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
            <i class="fas fa-history text-white text-xl"></i>
        </div>
        <div>
            <h3 class="text-xl font-bold text-gray-900">Order Timeline</h3>
            <p class="text-sm text-gray-600">Booking status history</p>
        </div>
    </div>
    
    <div class="space-y-4">
        <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-xl">
            <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                <i class="fas fa-plus text-white"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-900">Order Placed</p>
                <p class="text-sm text-gray-600"><?= date('M d, Y \a\t g:i A', strtotime($order['created_at'])) ?></p>
            </div>
        </div>
        
        <?php if ($order['status'] === 'confirmed'): ?>
        <div class="flex items-center gap-4 p-4 bg-green-50 rounded-xl">
            <div class="w-10 h-10 bg-green-600 rounded-full flex items-center justify-center">
                <i class="fas fa-check text-white"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-900">Booking Confirmed</p>
                <p class="text-sm text-gray-600"><?= date('M d, Y \a\t g:i A', strtotime($order['updated_at'])) ?></p>
            </div>
        </div>
        <?php elseif ($order['status'] === 'cancelled'): ?>
        <div class="flex items-center gap-4 p-4 bg-red-50 rounded-xl">
            <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center">
                <i class="fas fa-times text-white"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-900">Booking Cancelled</p>
                <p class="text-sm text-gray-600"><?= date('M d, Y \a\t g:i A', strtotime($order['updated_at'])) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'views/layouts/app.php';
?>
