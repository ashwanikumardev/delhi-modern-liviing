<?php
$title = 'Edit Room - Admin Panel';
$currentPage = 'rooms';
ob_start();
?>

<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50">
    <!-- Header -->
    <div class="bg-white/80 backdrop-blur-sm border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center gap-4">
                    <a href="<?= url('admin/rooms') ?>" class="flex items-center gap-2 text-gray-600 hover:text-gray-900 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        <span>Back to Rooms</span>
                    </a>
                    <div class="h-6 w-px bg-gray-300"></div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                        Edit Room
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="p-8">
                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    
                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="lg:col-span-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">Room Title *</label>
                            <input type="text" id="title" name="title" value="<?= htmlspecialchars($room['title'] ?? '') ?>" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        
                        <div class="lg:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description *</label>
                            <textarea id="description" name="description" rows="4" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"><?= htmlspecialchars($room['description'] ?? '') ?></textarea>
                        </div>
                        
                        <div>
                            <label for="price_per_month" class="block text-sm font-semibold text-gray-700 mb-2">Monthly Rent (₹) *</label>
                            <input type="number" id="price_per_month" name="price_per_month" value="<?= $room['price_per_month'] ?? '' ?>" required min="0" step="0.01"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        
                        <div>
                            <label for="deposit" class="block text-sm font-semibold text-gray-700 mb-2">Security Deposit (₹) *</label>
                            <input type="number" id="deposit" name="deposit" value="<?= $room['deposit'] ?? '' ?>" required min="0" step="0.01"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                        
                        <div>
                            <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Category *</label>
                            <select id="category" name="category" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="">Select Category</option>
                                <option value="single" <?= ($room['category'] ?? '') === 'single' ? 'selected' : '' ?>>Single Room</option>
                                <option value="double" <?= ($room['category'] ?? '') === 'double' ? 'selected' : '' ?>>Double Sharing</option>
                                <option value="male" <?= ($room['category'] ?? '') === 'male' ? 'selected' : '' ?>>Male PG</option>
                                <option value="female" <?= ($room['category'] ?? '') === 'female' ? 'selected' : '' ?>>Female PG</option>
                                <option value="pg" <?= ($room['category'] ?? '') === 'pg' ? 'selected' : '' ?>>General PG</option>
                            </select>
                        </div>
                        
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select id="status" name="status"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                <option value="active" <?= ($room['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= ($room['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <!-- Location Information -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Location Details</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="lg:col-span-2">
                                <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">Full Address *</label>
                                <textarea id="address" name="address" rows="3" required
                                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"><?= htmlspecialchars($room['address'] ?? '') ?></textarea>
                            </div>
                            
                            <div>
                                <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">City *</label>
                                <input type="text" id="city" name="city" value="<?= htmlspecialchars($room['city'] ?? 'Delhi') ?>" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                            
                            <div>
                                <label for="pincode" class="block text-sm font-semibold text-gray-700 mb-2">Pincode *</label>
                                <input type="text" id="pincode" name="pincode" value="<?= htmlspecialchars($room['pincode'] ?? '') ?>" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Availability -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Availability</h3>
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div>
                                <label for="availability_status" class="block text-sm font-semibold text-gray-700 mb-2">Availability Status</label>
                                <select id="availability_status" name="availability_status"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                    <option value="available" <?= ($room['availability_status'] ?? '') === 'available' ? 'selected' : '' ?>>Available</option>
                                    <option value="occupied" <?= ($room['availability_status'] ?? '') === 'occupied' ? 'selected' : '' ?>>Occupied</option>
                                    <option value="maintenance" <?= ($room['availability_status'] ?? '') === 'maintenance' ? 'selected' : '' ?>>Under Maintenance</option>
                                </select>
                            </div>
                            
                            <div class="flex items-center">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input type="checkbox" name="featured" value="1" <?= ($room['featured'] ?? 0) ? 'checked' : '' ?>
                                           class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-sm font-semibold text-gray-700">Featured Room</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Room Images -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Room Images</h3>

                        <!-- Existing Images -->
                        <?php
                        require_once 'helpers/ImageHelper.php';
                        $existingImages = json_decode($room['images'] ?? '[]', true);
                        ?>
                        <?php if (!empty($existingImages)): ?>
                            <div class="mb-4">
                                <p class="text-sm text-gray-600 mb-2">Current Images:</p>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    <?php foreach ($existingImages as $index => $image): ?>
                                        <div class="relative group">
                                            <img src="<?= ImageHelper::getImageUrl($image) ?>"
                                                 alt="Room image <?= $index + 1 ?>"
                                                 class="w-full h-24 object-cover rounded-lg">
                                            <button type="button"
                                                    onclick="removeImage('<?= htmlspecialchars($image) ?>', this)"
                                                    class="absolute top-1 right-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                                ×
                                            </button>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Upload New Images -->
                        <div>
                            <label for="images" class="block text-sm font-semibold text-gray-700 mb-2">
                                Upload Additional Images
                            </label>
                            <input type="file" id="images" name="images[]" multiple accept="image/*"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                            <p class="text-xs text-gray-500 mt-2">Supported formats: JPG, PNG, GIF. Max size: 5MB per image.</p>
                        </div>
                    </div>

                    <!-- Amenities -->
                    <div class="border-t pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Amenities</h3>
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <?php 
                            $currentAmenities = json_decode($room['amenities'] ?? '[]', true);
                            $availableAmenities = ['AC', 'WiFi', 'Meals', 'Laundry', 'Security', 'Parking', 'Gym', 'Common Area', 'Study Room', 'Library', 'Housekeeping'];
                            foreach ($availableAmenities as $amenity): 
                            ?>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" name="amenities[]" value="<?= $amenity ?>" 
                                           <?= in_array($amenity, $currentAmenities) ? 'checked' : '' ?>
                                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <span class="text-sm text-gray-700"><?= $amenity ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="border-t pt-6 flex justify-end gap-4">
                        <a href="<?= url('admin/rooms') ?>" 
                           class="px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-semibold">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all font-semibold shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Update Room
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function removeImage(imageName, button) {
    if (confirm('Are you sure you want to remove this image?')) {
        // Remove the image element from display
        button.parentElement.remove();

        // You could also send an AJAX request to delete the image from server
        // For now, we'll just hide it from the UI
        console.log('Image removed:', imageName);
    }
}

// Image preview for new uploads
document.getElementById('images').addEventListener('change', function() {
    const files = this.files;
    if (files.length > 5) {
        alert('Maximum 5 images allowed');
        this.value = '';
    }
});
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/admin.php';
?>
