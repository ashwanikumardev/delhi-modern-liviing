<?php
$title = 'Shopping Cart - Delhi Modern Living';
ob_start();
?>

<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Shopping Cart</h1>
            <p class="text-gray-600 mt-2">Review your selected rooms before checkout</p>
        </div>
        
        <?php if (empty($cart_items)): ?>
            <!-- Empty Cart -->
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <div class="text-6xl text-gray-300 mb-4">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h2 class="text-2xl font-semibold text-gray-900 mb-2">Your cart is empty</h2>
                <p class="text-gray-600 mb-6">Start browsing our rooms to add them to your cart</p>
                <a href="<?= url('rooms') ?>" class="bg-primary-600 text-white hover:bg-primary-700 px-6 py-3 rounded-lg font-semibold transition duration-300">
                    Browse Rooms
                </a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-md">
                        <div class="p-6 border-b border-gray-200">
                            <h2 class="text-xl font-semibold text-gray-900">
                                Cart Items (<?= count($cart_items) ?>)
                            </h2>
                        </div>
                        
                        <div class="divide-y divide-gray-200">
                            <?php
                            require_once 'helpers/ImageHelper.php';
                            require_once 'helpers/AmenityHelper.php';
                            foreach ($cart_items as $item):
                                $images = json_decode($item['images'] ?? '[]', true);
                                $itemTotal = $item['price_per_month'] * $item['months_count'];
                            ?>
                                <div class="p-6" id="cart-item-<?= $item['room_id'] ?>">
                                    <div class="flex items-start space-x-4">
                                        <!-- Room Image -->
                                        <div class="flex-shrink-0">
                                            <img src="<?= ImageHelper::getImageUrl($images[0] ?? null) ?>"
                                                 alt="<?= htmlspecialchars($item['title']) ?>"
                                                 class="w-20 h-20 object-cover rounded-lg"
                                                 onerror="this.src='<?= url('assets/images/placeholder-room.jpg') ?>'">
                                        </div>
                                        
                                        <!-- Room Details -->
                                        <div class="flex-1 min-w-0">
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                                <a href="<?= url('rooms/' . $item['room_id']) ?>" class="hover:text-primary-600">
                                                    <?= htmlspecialchars($item['title']) ?>
                                                </a>
                                            </h3>
                                            <div class="text-gray-600 text-sm mb-2">
                                                <?= AmenityHelper::renderLocation($item['address'], $item['city'] ?? null, $item['pincode'] ?? null) ?>
                                            </div>
                                            
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                                <div class="mb-2 sm:mb-0">
                                                    <span class="text-sm text-gray-600">Duration:</span>
                                                    <span class="font-medium">
                                                        <?= date('M d, Y', strtotime($item['start_date'])) ?> - 
                                                        <?= date('M d, Y', strtotime($item['end_date'])) ?>
                                                    </span>
                                                    <span class="text-sm text-gray-600">
                                                        (<?= $item['months_count'] ?> months)
                                                    </span>
                                                </div>
                                                
                                                <div class="flex items-center space-x-4">
                                                    <div class="text-right">
                                                        <div class="text-sm text-gray-600">
                                                            ₹<?= number_format($item['price_per_month']) ?>/month
                                                        </div>
                                                        <div class="text-lg font-semibold text-primary-600">
                                                            ₹<?= number_format($itemTotal) ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <button onclick="removeFromCart(<?= $item['room_id'] ?>)" 
                                                            class="text-red-600 hover:text-red-800 p-2">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Order Summary</h2>
                        
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">₹<?= number_format($cart_total) ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">GST (18%)</span>
                                <span class="font-medium">₹<?= number_format($gst_amount) ?></span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold">Total</span>
                                    <span class="text-lg font-semibold text-primary-600">
                                        ₹<?= number_format($total_with_gst) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Coupon Code -->
                        <div class="mb-6">
                            <label for="coupon_code" class="block text-sm font-medium text-gray-700 mb-2">
                                Coupon Code
                            </label>
                            <div class="flex">
                                <input type="text" id="coupon_code" name="coupon_code" 
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                                       placeholder="Enter coupon code">
                                <button type="button" onclick="applyCoupon()" 
                                        class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded-r-md border border-l-0 border-gray-300">
                                    Apply
                                </button>
                            </div>
                        </div>
                        
                        <!-- Checkout Button -->
                        <a href="<?= url('checkout') ?>" 
                           class="w-full bg-primary-600 text-white hover:bg-primary-700 py-3 px-4 rounded-lg font-semibold text-center block transition duration-300">
                            Proceed to Checkout
                        </a>
                        
                        <!-- Continue Shopping -->
                        <a href="<?= url('rooms') ?>" 
                           class="w-full bg-gray-100 text-gray-700 hover:bg-gray-200 py-2 px-4 rounded-lg font-medium text-center block mt-3 transition duration-300">
                            Continue Shopping
                        </a>
                        
                        <!-- Security Info -->
                        <div class="mt-6 text-center">
                            <div class="flex items-center justify-center text-sm text-gray-600">
                                <i class="fas fa-lock text-green-500 mr-2"></i>
                                Secure checkout with SSL encryption
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function removeFromCart(roomId) {
    if (!confirm('Are you sure you want to remove this room from your cart?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('room_id', roomId);
    formData.append('csrf_token', '<?= $csrf_token ?>');
    
    fetch((window.API_BASE || '/api') + '/cart/remove', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove item from DOM
            document.getElementById('cart-item-' + roomId).remove();
            
            // Update cart count in navigation
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                if (data.cart_count > 0) {
                    cartCountElement.textContent = data.cart_count;
                    cartCountElement.classList.remove('hidden');
                } else {
                    cartCountElement.classList.add('hidden');
                }
            }
            
            // Reload page if cart is empty
            if (data.cart_count === 0) {
                location.reload();
            }
            
            // Show success message
            showMessage(data.message, 'success');
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('An error occurred. Please try again.', 'error');
    });
}

function applyCoupon() {
    const couponCode = document.getElementById('coupon_code').value.trim();
    
    if (!couponCode) {
        showMessage('Please enter a coupon code', 'error');
        return;
    }
    
    // TODO: Implement coupon application
    showMessage('Coupon functionality will be implemented soon', 'info');
}

function showMessage(message, type) {
    // Create and show flash message
    const messageDiv = document.createElement('div');
    messageDiv.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded shadow-lg ${
        type === 'success' ? 'bg-green-100 border border-green-400 text-green-700' :
        type === 'error' ? 'bg-red-100 border border-red-400 text-red-700' :
        'bg-blue-100 border border-blue-400 text-blue-700'
    }`;
    messageDiv.innerHTML = `
        <div class="flex justify-between items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(messageDiv);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (messageDiv.parentNode) {
            messageDiv.parentNode.removeChild(messageDiv);
        }
    }, 5000);
}
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/app.php';
?>
