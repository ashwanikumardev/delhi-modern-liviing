<?php
$page_title = 'Create Order';
ob_start();
?>

<!-- Modern Order Creation Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">
                Create New Order
            </h1>
            <p class="text-gray-600 mt-2">Manually create a complete order with all details</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= url('admin/orders') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span class="font-semibold">Back to Orders</span>
            </a>
        </div>
    </div>
</div>

<!-- Order Creation Form -->
<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
    <form method="POST" action="<?= url('admin/orders/create') ?>" class="space-y-8">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        
        <!-- Customer Selection Section -->
        <div class="border-b border-gray-200 pb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Customer Information</h3>
                    <p class="text-sm text-gray-600">Select the customer for this order</p>
                </div>
            </div>
            
            <div>
                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Select Customer <span class="text-red-500">*</span>
                </label>
                <select id="user_id" name="user_id" required onchange="updateCustomerDetails()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                    <option value="">Choose a customer...</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>" 
                                data-name="<?= htmlspecialchars($user['name']) ?>"
                                data-email="<?= htmlspecialchars($user['email']) ?>"
                                data-phone="<?= htmlspecialchars($user['phone'] ?? '') ?>">
                            <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Customer Details Display -->
            <div id="customer-details" class="mt-4 p-4 bg-blue-50 rounded-lg hidden">
                <h4 class="font-semibold text-gray-900 mb-3">Customer Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="font-semibold text-gray-700">Name:</span>
                        <span id="customer-name" class="text-gray-900">-</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700">Email:</span>
                        <span id="customer-email" class="text-gray-900">-</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700">Phone:</span>
                        <span id="customer-phone" class="text-gray-900">-</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Room Selection Section -->
        <div class="border-b border-gray-200 pb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-home text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Room Selection</h3>
                    <p class="text-sm text-gray-600">Select the room for this order</p>
                </div>
            </div>
            
            <div>
                <label for="room_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Select Room <span class="text-red-500">*</span>
                </label>
                <select id="room_id" name="room_id" required onchange="updateRoomDetails()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                    <option value="">Choose a room...</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?= $room['id'] ?>" 
                                data-title="<?= htmlspecialchars($room['title']) ?>"
                                data-price="<?= $room['price_per_month'] ?>" 
                                data-deposit="<?= $room['deposit'] ?>"
                                data-address="<?= htmlspecialchars($room['address']) ?>"
                                data-city="<?= htmlspecialchars($room['city']) ?>"
                                data-status="<?= $room['availability_status'] ?>">
                            <?= htmlspecialchars($room['title']) ?> - ₹<?= number_format($room['price_per_month']) ?>/month (<?= htmlspecialchars($room['city']) ?>)
                            <?php if ($room['availability_status'] !== 'available'): ?>
                                - <?= ucfirst($room['availability_status']) ?>
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Room Details Display -->
            <div id="room-details" class="mt-4 p-4 bg-green-50 rounded-lg hidden">
                <h4 class="font-semibold text-gray-900 mb-3">Room Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                    <div>
                        <span class="font-semibold text-gray-700">Monthly Rent:</span>
                        <span id="room-price" class="text-green-600 font-bold">₹0</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700">Security Deposit:</span>
                        <span id="room-deposit" class="text-blue-600 font-bold">₹0</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700">Location:</span>
                        <span id="room-location" class="text-gray-900">-</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700">Status:</span>
                        <span id="room-status" class="text-gray-900">-</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Booking Period Section -->
        <div class="border-b border-gray-200 pb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Booking Period</h3>
                    <p class="text-sm text-gray-600">Set the booking dates and duration</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        Start Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="start_date" name="start_date" required
                           min="<?= date('Y-m-d') ?>"
                           onchange="calculateEndDate()"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                </div>
                
                <div>
                    <label for="months_count" class="block text-sm font-semibold text-gray-700 mb-2">
                        Duration (Months) <span class="text-red-500">*</span>
                    </label>
                    <select id="months_count" name="months_count" required onchange="calculateEndDate(); calculateTotal()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select duration...</option>
                        <option value="1">1 Month</option>
                        <option value="2">2 Months</option>
                        <option value="3">3 Months</option>
                        <option value="6">6 Months</option>
                        <option value="12">12 Months</option>
                        <option value="24">24 Months</option>
                    </select>
                </div>
            </div>
            
            <!-- End Date Display -->
            <div id="end-date-display" class="mt-4 p-4 bg-purple-50 rounded-lg hidden">
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar-check text-purple-600"></i>
                    <span class="font-semibold text-gray-700">End Date:</span>
                    <span id="calculated-end-date" class="font-bold text-purple-600">-</span>
                </div>
            </div>
        </div>
        
        <!-- Payment & Pricing Section -->
        <div class="border-b border-gray-200 pb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-rupee-sign text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Payment & Pricing</h3>
                    <p class="text-sm text-gray-600">Configure pricing and additional charges</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <label for="gst_amount" class="block text-sm font-semibold text-gray-700 mb-2">
                        GST Amount (₹)
                    </label>
                    <input type="number" id="gst_amount" name="gst_amount" step="0.01" min="0"
                           placeholder="0.00" onchange="calculateTotal()"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                </div>
                
                <div>
                    <label for="discount_amount" class="block text-sm font-semibold text-gray-700 mb-2">
                        Discount Amount (₹)
                    </label>
                    <input type="number" id="discount_amount" name="discount_amount" step="0.01" min="0"
                           placeholder="0.00" onchange="calculateTotal()"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                </div>
                
                <div>
                    <label for="coupon_code" class="block text-sm font-semibold text-gray-700 mb-2">
                        Coupon Code
                    </label>
                    <input type="text" id="coupon_code" name="coupon_code"
                           placeholder="Enter coupon code"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                </div>
                
                <div>
                    <label for="payment_method" class="block text-sm font-semibold text-gray-700 mb-2">
                        Payment Method
                    </label>
                    <select id="payment_method" name="payment_method"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select method...</option>
                        <option value="cash">Cash</option>
                        <option value="card">Credit/Debit Card</option>
                        <option value="upi">UPI</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="cheque">Cheque</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <label for="transaction_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Transaction ID / Reference Number
                </label>
                <input type="text" id="transaction_id" name="transaction_id"
                       placeholder="Enter transaction ID or reference number"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
            </div>
            
            <!-- Total Calculation Display -->
            <div id="total-calculation" class="mt-6 p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl hidden">
                <h4 class="font-bold text-gray-900 mb-4 text-lg">Order Summary</h4>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span>Monthly Rent × <span id="calc-months">0</span> months:</span>
                        <span id="calc-rent" class="font-semibold">₹0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Security Deposit:</span>
                        <span id="calc-deposit" class="font-semibold">₹0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>GST Amount:</span>
                        <span id="calc-gst" class="font-semibold">₹0</span>
                    </div>
                    <div class="flex justify-between text-red-600">
                        <span>Discount:</span>
                        <span id="calc-discount" class="font-semibold">-₹0</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg border-t pt-3 text-green-600">
                        <span>Total Amount:</span>
                        <span id="calc-total">₹0</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Status Section -->
        <div class="border-b border-gray-200 pb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-cog text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Order Status</h3>
                    <p class="text-sm text-gray-600">Set initial order and payment status</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="booking_status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Booking Status
                    </label>
                    <select id="booking_status" name="booking_status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                        <option value="confirmed">Confirmed</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                
                <div>
                    <label for="payment_status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Payment Status
                    </label>
                    <select id="payment_status" name="payment_status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="failed">Failed</option>
                        <option value="refunded">Refunded</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Submit Button -->
        <div class="flex justify-end gap-4">
            <a href="<?= url('admin/orders') ?>" class="px-8 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 font-semibold">
                Cancel
            </a>
            <button type="submit" class="px-8 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all duration-300 font-semibold shadow-lg">
                <i class="fas fa-plus mr-2"></i>Create Order
            </button>
        </div>
    </form>
</div>

<script>
function updateCustomerDetails() {
    const userSelect = document.getElementById('user_id');
    const selectedOption = userSelect.options[userSelect.selectedIndex];
    const customerDetails = document.getElementById('customer-details');
    
    if (selectedOption.value) {
        document.getElementById('customer-name').textContent = selectedOption.dataset.name;
        document.getElementById('customer-email').textContent = selectedOption.dataset.email;
        document.getElementById('customer-phone').textContent = selectedOption.dataset.phone || 'N/A';
        customerDetails.classList.remove('hidden');
    } else {
        customerDetails.classList.add('hidden');
    }
}

function updateRoomDetails() {
    const roomSelect = document.getElementById('room_id');
    const selectedOption = roomSelect.options[roomSelect.selectedIndex];
    const roomDetails = document.getElementById('room-details');
    
    if (selectedOption.value) {
        const price = selectedOption.dataset.price;
        const deposit = selectedOption.dataset.deposit;
        
        document.getElementById('room-price').textContent = '₹' + parseInt(price).toLocaleString();
        document.getElementById('room-deposit').textContent = '₹' + parseInt(deposit).toLocaleString();
        document.getElementById('room-location').textContent = selectedOption.dataset.address + ', ' + selectedOption.dataset.city;
        document.getElementById('room-status').textContent = selectedOption.dataset.status;
        
        roomDetails.classList.remove('hidden');
        calculateTotal();
    } else {
        roomDetails.classList.add('hidden');
        document.getElementById('total-calculation').classList.add('hidden');
    }
}

function calculateEndDate() {
    const startDate = document.getElementById('start_date').value;
    const months = document.getElementById('months_count').value;
    const endDateDisplay = document.getElementById('end-date-display');
    
    if (startDate && months) {
        const start = new Date(startDate);
        const end = new Date(start.getFullYear(), start.getMonth() + parseInt(months), start.getDate());
        
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        document.getElementById('calculated-end-date').textContent = end.toLocaleDateString('en-US', options);
        endDateDisplay.classList.remove('hidden');
    } else {
        endDateDisplay.classList.add('hidden');
    }
}

function calculateTotal() {
    const roomSelect = document.getElementById('room_id');
    const monthsSelect = document.getElementById('months_count');
    const gstAmount = parseFloat(document.getElementById('gst_amount').value) || 0;
    const discountAmount = parseFloat(document.getElementById('discount_amount').value) || 0;
    const totalCalc = document.getElementById('total-calculation');
    
    if (roomSelect.value && monthsSelect.value) {
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        const price = parseInt(selectedOption.dataset.price);
        const deposit = parseInt(selectedOption.dataset.deposit);
        const months = parseInt(monthsSelect.value);
        
        const rentTotal = price * months;
        const grandTotal = rentTotal + deposit + gstAmount - discountAmount;
        
        document.getElementById('calc-months').textContent = months;
        document.getElementById('calc-rent').textContent = '₹' + rentTotal.toLocaleString();
        document.getElementById('calc-deposit').textContent = '₹' + deposit.toLocaleString();
        document.getElementById('calc-gst').textContent = '₹' + gstAmount.toLocaleString();
        document.getElementById('calc-discount').textContent = '₹' + discountAmount.toLocaleString();
        document.getElementById('calc-total').textContent = '₹' + grandTotal.toLocaleString();
        
        totalCalc.classList.remove('hidden');
    } else {
        totalCalc.classList.add('hidden');
    }
}
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/admin.php';
?>
