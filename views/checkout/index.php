<?php
$title = 'Checkout - Delhi Modern Living';
ob_start();
?>

<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
            <p class="text-gray-600 mt-2">Complete your booking</p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <form action="/checkout" method="POST" class="space-y-8">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <!-- Billing Information -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Billing Information</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="billing_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" id="billing_name" name="billing_name" required
                                       value="<?= htmlspecialchars($user['name']) ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            
                            <div>
                                <label for="billing_email" class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                                <input type="email" id="billing_email" name="billing_email" required
                                       value="<?= htmlspecialchars($user['email']) ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            
                            <div>
                                <label for="billing_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                                <input type="tel" id="billing_phone" name="billing_phone" required
                                       value="<?= htmlspecialchars($user['phone']) ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            
                            <div>
                                <label for="billing_city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                <input type="text" id="billing_city" name="billing_city"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                            <textarea id="billing_address" name="billing_address" rows="3" required
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                      placeholder="Enter your complete address"></textarea>
                        </div>
                    </div>
                    
                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Payment Method</h2>
                        
                        <div class="space-y-4">
                            <!-- Razorpay -->
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="razorpay" class="text-primary-600 focus:ring-primary-500" checked>
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-900">Online Payment</p>
                                            <p class="text-sm text-gray-600">Pay securely with UPI, Cards, Net Banking</p>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <img src="/assets/images/payment/razorpay.png" alt="Razorpay" class="h-6" onerror="this.style.display='none'">
                                            <i class="fab fa-cc-visa text-2xl text-blue-600"></i>
                                            <i class="fab fa-cc-mastercard text-2xl text-red-600"></i>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Bank Transfer -->
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="bank_transfer" class="text-primary-600 focus:ring-primary-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-900">Bank Transfer</p>
                                            <p class="text-sm text-gray-600">Transfer directly to our bank account</p>
                                        </div>
                                        <i class="fas fa-university text-2xl text-gray-600"></i>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Cash Payment -->
                            <label class="flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="payment_method" value="cash" class="text-primary-600 focus:ring-primary-500">
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-medium text-gray-900">Pay on Arrival</p>
                                            <p class="text-sm text-gray-600">Pay cash when you check-in</p>
                                        </div>
                                        <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                        
                        <!-- Bank Transfer Details (Hidden by default) -->
                        <div id="bank-transfer-details" class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden">
                            <h3 class="font-semibold text-blue-900 mb-2">Bank Transfer Details</h3>
                            <div class="text-sm text-blue-800 space-y-1">
                                <p><strong>Account Name:</strong> Delhi Modern Living Pvt Ltd</p>
                                <p><strong>Account Number:</strong> 1234567890</p>
                                <p><strong>IFSC Code:</strong> HDFC0001234</p>
                                <p><strong>Bank:</strong> HDFC Bank, Connaught Place Branch</p>
                                <p class="mt-2 text-blue-700">Please share the transaction receipt via WhatsApp: +91-9876543210</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Terms and Conditions -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-start">
                            <input type="checkbox" id="terms" name="terms" required
                                   class="mt-1 h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                            <label for="terms" class="ml-2 text-sm text-gray-700">
                                I agree to the 
                                <a href="/terms" target="_blank" class="text-primary-600 hover:text-primary-500">Terms and Conditions</a>
                                and 
                                <a href="/privacy" target="_blank" class="text-primary-600 hover:text-primary-500">Privacy Policy</a>.
                                I understand the cancellation policy and refund terms.
                            </label>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-primary-600 text-white hover:bg-primary-700 px-8 py-3 rounded-lg font-semibold transition duration-300 flex items-center">
                            <i class="fas fa-credit-card mr-2"></i>
                            Complete Booking
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                    
                    <!-- Cart Items -->
                    <div class="space-y-4 mb-6">
                        <?php
                        require_once 'helpers/ImageHelper.php';
                        require_once 'helpers/AmenityHelper.php';
                        foreach ($cart_items as $item):
                            $images = json_decode($item['images'] ?? '[]', true);
                            $itemTotal = $item['price_per_month'] * $item['months_count'];
                        ?>
                            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                <img src="<?= ImageHelper::getImageUrl($images[0] ?? null) ?>"
                                     alt="<?= htmlspecialchars($item['title']) ?>"
                                     class="w-16 h-16 object-cover rounded-lg"
                                     onerror="this.src='<?= url('assets/images/placeholder-room.jpg') ?>'">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-gray-900 truncate">
                                        <?= htmlspecialchars($item['title']) ?>
                                    </h3>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <?= date('M d', strtotime($item['start_date'])) ?> - 
                                        <?= date('M d, Y', strtotime($item['end_date'])) ?>
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        <?= $item['months_count'] ?> months
                                    </p>
                                    <div class="mt-2">
                                        <span class="text-sm font-semibold text-primary-600">
                                            ₹<?= number_format($itemTotal) ?>
                                        </span>
                                        <span class="text-xs text-gray-500">+ ₹<?= number_format($item['deposit']) ?> deposit</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Cost Breakdown -->
                    <div class="space-y-3 mb-6 pb-6 border-b border-gray-200">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">₹<?= number_format($subtotal) ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">GST (18%)</span>
                            <span class="font-medium">₹<?= number_format($gst_amount) ?></span>
                        </div>
                        <div class="flex justify-between text-lg font-semibold">
                            <span>Total Amount</span>
                            <span class="text-primary-600">₹<?= number_format($total_amount) ?></span>
                        </div>
                    </div>
                    
                    <!-- Security Features -->
                    <div class="space-y-2 text-sm text-gray-600">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                            <span>Secure SSL encryption</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-undo text-blue-500 mr-2"></i>
                            <span>Flexible cancellation policy</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-headset text-purple-500 mr-2"></i>
                            <span>24/7 customer support</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Show/hide bank transfer details
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const bankDetails = document.getElementById('bank-transfer-details');
        if (this.value === 'bank_transfer') {
            bankDetails.classList.remove('hidden');
        } else {
            bankDetails.classList.add('hidden');
        }
    });
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = ['billing_name', 'billing_email', 'billing_phone', 'billing_address'];
    let hasErrors = false;
    
    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input.value.trim()) {
            input.classList.add('border-red-500');
            hasErrors = true;
        } else {
            input.classList.remove('border-red-500');
        }
    });
    
    // Email validation
    const email = document.getElementById('billing_email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email.value && !emailRegex.test(email.value)) {
        email.classList.add('border-red-500');
        hasErrors = true;
    }
    
    // Terms checkbox
    const terms = document.getElementById('terms');
    if (!terms.checked) {
        alert('Please accept the terms and conditions to continue.');
        hasErrors = true;
    }
    
    if (hasErrors) {
        e.preventDefault();
        alert('Please fill all required fields correctly.');
    }
});
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/app.php';
?>
