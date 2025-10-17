<?php
$title = htmlspecialchars($room['title']) . ' - Delhi Modern Living';
$description = 'Book ' . htmlspecialchars($room['title']) . ' in ' . htmlspecialchars($room['city']) . '. Starting from ₹' . number_format($room['price_per_month']) . '/month.';
ob_start();
?>

<div class="bg-gray-50 min-h-screen">
    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="<?= url('') ?>" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="<?= url('rooms') ?>" class="text-gray-400 hover:text-gray-500">Rooms</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="text-gray-900 font-medium"><?= htmlspecialchars($room['title']) ?></span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Image Gallery -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                    <div class="relative">
                        <?php
                        require_once 'helpers/ImageHelper.php';
                        require_once 'helpers/AmenityHelper.php';
                        $images = $room['images'] ?? [];
                        ?>
                        <?php if (!empty($images)): ?>
                            <div class="image-gallery">
                                <div class="main-image">
                                    <img id="main-image" src="<?= ImageHelper::getImageUrl($images[0]) ?>"
                                         alt="<?= htmlspecialchars($room['title']) ?>"
                                         class="w-full h-96 object-cover"
                                         onerror="this.src='<?= url('assets/images/placeholder-room.jpg') ?>'">
                                </div>
                                <?php if (count($images) > 1): ?>
                                    <div class="thumbnail-grid mt-4 grid grid-cols-4 gap-2 p-4">
                                        <?php foreach ($images as $index => $image): ?>
                                            <img src="<?= ImageHelper::getImageUrl($image) ?>"
                                                 alt="Room image <?= $index + 1 ?>"
                                                 class="w-full h-20 object-cover rounded cursor-pointer hover:opacity-75 transition duration-300 <?= $index === 0 ? 'ring-2 ring-primary-500' : '' ?>"
                                                 onclick="changeMainImage(this, <?= $index ?>)"
                                                 onerror="this.style.display='none'">
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <img src="<?= url('assets/images/placeholder-room.jpg') ?>"
                                 alt="<?= htmlspecialchars($room['title']) ?>"
                                 class="w-full h-96 object-cover">
                        <?php endif; ?>
                        
                        <!-- Status Badges -->
                        <div class="absolute top-4 left-4 flex space-x-2">
                            <span class="bg-primary-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                <?= ucfirst($room['category']) ?>
                            </span>
                            <span class="<?= $room['availability_status'] === 'available' ? 'bg-green-500' : 'bg-red-500' ?> text-white px-3 py-1 rounded-full text-sm font-semibold">
                                <?= ucfirst($room['availability_status']) ?>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Room Details -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($room['title']) ?></h1>
                            <?= AmenityHelper::renderLocation($room['address'], $room['city'], $room['pincode']) ?>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl font-bold text-primary-600">
                                ₹<?= number_format($room['price_per_month']) ?>
                            </div>
                            <div class="text-gray-600">per month</div>
                            <div class="text-sm text-gray-500 mt-1">
                                Security Deposit: ₹<?= number_format($room['deposit']) ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <?php if (!empty($room['description'])): ?>
                        <div class="mb-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-3">Description</h2>
                            <p class="text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($room['description'])) ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Amenities -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">Amenities</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                            <?php
                            $amenitiesList = !empty($room['amenities']) ? $room['amenities'] : [
                                'AC', 'WiFi', 'Furnished', 'Meals', 'Laundry',
                                'Security', 'Housekeeping', 'Power Backup', 'Gym', 'Swimming Pool'
                            ];
                            foreach ($amenitiesList as $amenity):
                            ?>
                                <div class="flex items-center text-gray-700 bg-gray-50 p-3 rounded-lg border">
                                    <i class="<?= AmenityHelper::getAmenityIcon($amenity) ?> <?= AmenityHelper::getAmenityColor($amenity) ?> mr-2"></i>
                                    <?= htmlspecialchars($amenity) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- House Rules -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-3">House Rules</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-gray-700">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-primary-600 mr-2"></i>
                                Check-in: 12:00 PM onwards
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-primary-600 mr-2"></i>
                                Check-out: 11:00 AM
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-ban text-red-500 mr-2"></i>
                                No smoking inside
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-volume-down text-primary-600 mr-2"></i>
                                Quiet hours: 10 PM - 7 AM
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-users text-primary-600 mr-2"></i>
                                Visitors allowed till 9 PM
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                                24/7 Security
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Location Map -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Location</h2>
                    <div class="bg-gray-200 h-64 rounded-lg flex items-center justify-center">
                        <div class="text-center text-gray-600">
                            <i class="fas fa-map-marker-alt text-4xl mb-2"></i>
                            <p><?= htmlspecialchars($room['address']) ?></p>
                            <p class="text-sm">Map integration coming soon</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Booking Sidebar -->
            <div class="lg:col-span-1 mt-8 lg:mt-0">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Book This Room</h2>
                    
                    <?php if ($room['availability_status'] !== 'available'): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            <p class="font-semibold">Currently Not Available</p>
                            <p class="text-sm">This room is currently occupied. Please check back later or browse other available rooms.</p>
                        </div>
                        <a href="<?= url('rooms') ?>" class="w-full bg-gray-600 text-white py-3 px-4 rounded-lg font-semibold text-center block">
                            Browse Other Rooms
                        </a>
                    <?php elseif (!$is_logged_in): ?>
                        <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded mb-4">
                            <p class="font-semibold">Login Required</p>
                            <p class="text-sm">Please login to book this room.</p>
                        </div>
                        <a href="<?= url('auth/login') ?>" class="w-full bg-primary-600 text-white hover:bg-primary-700 py-3 px-4 rounded-lg font-semibold text-center block transition duration-300">
                            Login to Book
                        </a>
                    <?php else: ?>
                        <form id="booking-form" class="space-y-4">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <input type="hidden" name="room_id" value="<?= $room['id'] ?>">
                            
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Check-in Date</label>
                                <input type="date" id="start_date" name="start_date" required
                                       min="<?= date('Y-m-d') ?>"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Check-out Date</label>
                                <input type="date" id="end_date" name="end_date" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                            </div>
                            
                            <div>
                                <label for="months_count" class="block text-sm font-medium text-gray-700 mb-1">Duration (Months)</label>
                                <select id="months_count" name="months_count" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">Select duration</option>
                                    <option value="1">1 Month</option>
                                    <option value="2">2 Months</option>
                                    <option value="3">3 Months</option>
                                    <option value="6">6 Months</option>
                                    <option value="12">12 Months</option>
                                </select>
                            </div>
                            
                            <!-- Cost Breakdown -->
                            <div id="cost-breakdown" class="bg-gray-50 p-4 rounded-lg hidden">
                                <h3 class="font-semibold text-gray-900 mb-2">Cost Breakdown</h3>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span>Monthly Rent × <span id="duration-display">0</span> months</span>
                                        <span id="rent-total">₹0</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Security Deposit</span>
                                        <span>₹<?= number_format($room['deposit']) ?></span>
                                    </div>
                                    <div class="border-t pt-2 font-semibold flex justify-between">
                                        <span>Total Amount</span>
                                        <span id="total-amount">₹<?= number_format($room['deposit']) ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" 
                                    class="w-full bg-primary-600 text-white hover:bg-primary-700 py-3 px-4 rounded-lg font-semibold transition duration-300">
                                <i class="fas fa-shopping-cart mr-2"></i>Add to Cart
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <!-- Contact Info -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-3">Need Help?</h3>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-phone text-primary-600 mr-2"></i>
                                <span>+91-9876543210</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-envelope text-primary-600 mr-2"></i>
                                <span>info@delhimodernliving.com</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-clock text-primary-600 mr-2"></i>
                                <span>24/7 Support Available</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Rooms -->
        <?php if (!empty($related_rooms)): ?>
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Similar Rooms</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($related_rooms as $relatedRoom):
                        $relatedImages = $relatedRoom['images'] ?? [];
                        $relatedAmenities = $relatedRoom['amenities'] ?? [];
                    ?>
                        <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
                            <div class="relative">
                                <img src="<?= ImageHelper::getImageUrl($relatedImages[0] ?? null) ?>"
                                     alt="<?= htmlspecialchars($relatedRoom['title']) ?>"
                                     class="w-full h-40 object-cover"
                                     onerror="this.src='<?= url('assets/images/placeholder-room.jpg') ?>'">
                                <div class="absolute top-2 left-2">
                                    <span class="bg-primary-600 text-white px-2 py-1 rounded text-xs font-semibold">
                                        <?= ucfirst($relatedRoom['category']) ?>
                                    </span>
                                </div>
                            </div>
                            
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2">
                                    <?= htmlspecialchars($relatedRoom['title']) ?>
                                </h3>
                                <p class="text-gray-600 text-sm mb-2 flex items-center">
                                    <i class="fas fa-map-marker-alt text-primary-600 mr-1"></i>
                                    <?= htmlspecialchars($relatedRoom['city']) ?>
                                </p>
                                
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="text-lg font-bold text-primary-600">
                                            ₹<?= number_format($relatedRoom['price_per_month']) ?>
                                        </span>
                                        <span class="text-gray-600 text-sm">/month</span>
                                    </div>
                                    <a href="<?= url('rooms/' . $relatedRoom['id']) ?>" 
                                       class="bg-primary-600 text-white hover:bg-primary-700 px-3 py-1 rounded text-sm font-medium transition duration-300">
                                        View
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Image gallery functionality
function changeMainImage(thumbnail, index) {
    const mainImage = document.getElementById('main-image');
    const thumbnails = document.querySelectorAll('.thumbnail-grid img');
    
    // Update main image
    mainImage.src = thumbnail.src;
    
    // Update thumbnail selection
    thumbnails.forEach((thumb, i) => {
        if (i === index) {
            thumb.classList.add('ring-2', 'ring-primary-500');
        } else {
            thumb.classList.remove('ring-2', 'ring-primary-500');
        }
    });
}

// Booking form functionality
document.addEventListener('DOMContentLoaded', function() {
    const monthsSelect = document.getElementById('months_count');
    const costBreakdown = document.getElementById('cost-breakdown');
    const durationDisplay = document.getElementById('duration-display');
    const rentTotal = document.getElementById('rent-total');
    const totalAmount = document.getElementById('total-amount');
    
    const monthlyRent = <?= $room['price_per_month'] ?>;
    const deposit = <?= $room['deposit'] ?>;
    
    if (monthsSelect) {
        monthsSelect.addEventListener('change', function() {
            const months = parseInt(this.value);
            
            if (months > 0) {
                const rentCost = monthlyRent * months;
                const total = rentCost + deposit;
                
                durationDisplay.textContent = months;
                rentTotal.textContent = '₹' + rentCost.toLocaleString();
                totalAmount.textContent = '₹' + total.toLocaleString();
                
                costBreakdown.classList.remove('hidden');
            } else {
                costBreakdown.classList.add('hidden');
            }
        });
    }
    
    // Date validation
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    if (startDateInput && endDateInput) {
        startDateInput.addEventListener('change', function() {
            const startDate = new Date(this.value);
            const minEndDate = new Date(startDate);
            minEndDate.setDate(minEndDate.getDate() + 30); // Minimum 30 days
            
            endDateInput.min = minEndDate.toISOString().split('T')[0];
            if (endDateInput.value && new Date(endDateInput.value) <= startDate) {
                endDateInput.value = '';
            }
        });
    }
    
    // Form submission
    const bookingForm = document.getElementById('booking-form');
    if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch((window.API_BASE || '/api') + '/cart/add', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count in navigation
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement && data.cart_count > 0) {
                        cartCountElement.textContent = data.cart_count;
                        cartCountElement.classList.remove('hidden');
                    }
                    
                    // Show success message and redirect
                    alert('Room added to cart successfully!');
                    window.location.href = (window.BASE_PATH || '') + '/cart';
                } else {
                    alert(data.message || 'Failed to add room to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred. Please try again.');
            });
        });
    }
});
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/app.php';
?>
