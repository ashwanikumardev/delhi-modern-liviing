<?php
$page_title = 'Create Booking';
ob_start();
?>

<!-- Modern Booking Creation Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-600 to-red-600 bg-clip-text text-transparent">
                Create New Booking
            </h1>
            <p class="text-gray-600 mt-2">Manually create a booking for a user</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= url('admin/bookings') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span class="font-semibold">Back to Bookings</span>
            </a>
        </div>
    </div>
</div>

<!-- Booking Creation Form -->
<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
    <form method="POST" action="<?= url('admin/bookings/create') ?>" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        
        <!-- User Selection Section -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-user text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">User Selection</h3>
                    <p class="text-sm text-gray-600">Select the user for this booking</p>
                </div>
            </div>
            
            <div>
                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Select User <span class="text-red-500">*</span>
                </label>
                <select id="user_id" name="user_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    <option value="">Choose a user...</option>
                    <?php foreach ($users as $user): ?>
                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <!-- Room Selection Section -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-home text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Room Selection</h3>
                    <p class="text-sm text-gray-600">Select the room to book</p>
                </div>
            </div>
            
            <div>
                <label for="room_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    Select Room <span class="text-red-500">*</span>
                </label>
                <select id="room_id" name="room_id" required onchange="updateRoomDetails()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                    <option value="">Choose a room...</option>
                    <?php foreach ($rooms as $room): ?>
                        <option value="<?= $room['id'] ?>" 
                                data-price="<?= $room['price_per_month'] ?>" 
                                data-deposit="<?= $room['deposit'] ?>"
                                data-status="<?= $room['availability_status'] ?>">
                            <?= htmlspecialchars($room['title']) ?> - ₹<?= number_format($room['price_per_month']) ?>/month
                            <?php if ($room['availability_status'] !== 'available'): ?>
                                (<?= ucfirst($room['availability_status']) ?>)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- Room Details Display -->
            <div id="room-details" class="mt-4 p-4 bg-gray-50 rounded-lg hidden">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="font-semibold text-gray-700">Monthly Rent:</span>
                        <span id="room-price" class="text-green-600 font-bold">₹0</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700">Security Deposit:</span>
                        <span id="room-deposit" class="text-blue-600 font-bold">₹0</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Booking Details Section -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Booking Details</h3>
                    <p class="text-sm text-gray-600">Set the booking period and dates</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 mb-2">
                        Start Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="start_date" name="start_date" required
                           min="<?= date('Y-m-d') ?>"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                </div>
                
                <div>
                    <label for="months_count" class="block text-sm font-semibold text-gray-700 mb-2">
                        Duration (Months) <span class="text-red-500">*</span>
                    </label>
                    <select id="months_count" name="months_count" required onchange="calculateTotal()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select duration...</option>
                        <option value="1">1 Month</option>
                        <option value="2">2 Months</option>
                        <option value="3">3 Months</option>
                        <option value="6">6 Months</option>
                        <option value="12">12 Months</option>
                    </select>
                </div>
            </div>
            
            <!-- Total Calculation Display -->
            <div id="total-calculation" class="mt-6 p-4 bg-blue-50 rounded-lg hidden">
                <h4 class="font-semibold text-gray-900 mb-3">Booking Summary</h4>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Monthly Rent × <span id="calc-months">0</span> months:</span>
                        <span id="calc-rent">₹0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Security Deposit:</span>
                        <span id="calc-deposit">₹0</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg border-t pt-2">
                        <span>Total Amount:</span>
                        <span id="calc-total" class="text-green-600">₹0</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Status Section -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-cog text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Booking Status</h3>
                    <p class="text-sm text-gray-600">Set initial booking and payment status</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="booking_status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Booking Status
                    </label>
                    <select id="booking_status" name="booking_status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="confirmed">Confirmed</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                
                <div>
                    <label for="payment_status" class="block text-sm font-semibold text-gray-700 mb-2">
                        Payment Status
                    </label>
                    <select id="payment_status" name="payment_status"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
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
            <a href="<?= url('admin/bookings') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 font-semibold">
                Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-300 font-semibold">
                Create Booking
            </button>
        </div>
    </form>
</div>

<script>
function updateRoomDetails() {
    const roomSelect = document.getElementById('room_id');
    const selectedOption = roomSelect.options[roomSelect.selectedIndex];
    const roomDetails = document.getElementById('room-details');
    
    if (selectedOption.value) {
        const price = selectedOption.dataset.price;
        const deposit = selectedOption.dataset.deposit;
        
        document.getElementById('room-price').textContent = '₹' + parseInt(price).toLocaleString();
        document.getElementById('room-deposit').textContent = '₹' + parseInt(deposit).toLocaleString();
        
        roomDetails.classList.remove('hidden');
        calculateTotal();
    } else {
        roomDetails.classList.add('hidden');
        document.getElementById('total-calculation').classList.add('hidden');
    }
}

function calculateTotal() {
    const roomSelect = document.getElementById('room_id');
    const monthsSelect = document.getElementById('months_count');
    const totalCalc = document.getElementById('total-calculation');
    
    if (roomSelect.value && monthsSelect.value) {
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        const price = parseInt(selectedOption.dataset.price);
        const deposit = parseInt(selectedOption.dataset.deposit);
        const months = parseInt(monthsSelect.value);
        
        const rentTotal = price * months;
        const grandTotal = rentTotal + deposit;
        
        document.getElementById('calc-months').textContent = months;
        document.getElementById('calc-rent').textContent = '₹' + rentTotal.toLocaleString();
        document.getElementById('calc-deposit').textContent = '₹' + deposit.toLocaleString();
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
