<?php
$page_title = 'Create Room';
ob_start();
?>

<!-- Modern Room Creation Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent">
                Create New Room
            </h1>
            <p class="text-gray-600 mt-2">Add a new room to your property listings</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= url('admin/rooms') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-arrow-left"></i>
                <span class="font-semibold">Back to Rooms</span>
            </a>
        </div>
    </div>
</div>

<!-- Room Creation Form -->
<div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
    <form method="POST" action="<?= url('admin/rooms/create') ?>" enctype="multipart/form-data" class="space-y-6">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
        
        <!-- Basic Information Section -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-home text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Basic Information</h3>
                    <p class="text-sm text-gray-600">Enter the room's basic details</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                        Room Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                           placeholder="e.g., Luxury Single Room in Connaught Place">
                </div>
                
                <div>
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select id="category" name="category" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select Category</option>
                        <option value="single">Single Room</option>
                        <option value="double">Double Sharing</option>
                        <option value="male">Male PG</option>
                        <option value="female">Female PG</option>
                        <option value="pg">General PG</option>
                    </select>
                </div>
                
                <div>
                    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                        Monthly Rent (₹) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="price" name="price" required min="0" step="100"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                           placeholder="e.g., 15000">
                </div>
                
                <div>
                    <label for="security_deposit" class="block text-sm font-semibold text-gray-700 mb-2">
                        Security Deposit (₹)
                    </label>
                    <input type="number" id="security_deposit" name="security_deposit" min="0" step="100"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                           placeholder="e.g., 10000">
                </div>
            </div>
        </div>
        
        <!-- Location Information Section -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-map-marker-alt text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Location Details</h3>
                    <p class="text-sm text-gray-600">Specify the room's location</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="city" class="block text-sm font-semibold text-gray-700 mb-2">
                        City <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="city" name="city" required value="Delhi"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                           placeholder="e.g., Delhi">
                </div>

                <div>
                    <label for="pincode" class="block text-sm font-semibold text-gray-700 mb-2">
                        Pincode <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="pincode" name="pincode" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                           placeholder="e.g., 110001">
                </div>
                
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-semibold text-gray-700 mb-2">
                        Full Address <span class="text-red-500">*</span>
                    </label>
                    <textarea id="address" name="address" rows="3" required
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                              placeholder="Enter complete address with landmarks"></textarea>
                </div>
            </div>
        </div>
        
        <!-- Room Details Section -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-bed text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Room Specifications</h3>
                    <p class="text-sm text-gray-600">Room size and capacity details</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label for="capacity" class="block text-sm font-semibold text-gray-700 mb-2">
                        Room Capacity <span class="text-red-500">*</span>
                    </label>
                    <select id="capacity" name="capacity" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select Capacity</option>
                        <option value="1">1 Person</option>
                        <option value="2">2 Persons</option>
                        <option value="3">3 Persons</option>
                        <option value="4">4 Persons</option>
                    </select>
                </div>
                
                <div>
                    <label for="room_size" class="block text-sm font-semibold text-gray-700 mb-2">
                        Room Size (sq ft)
                    </label>
                    <input type="number" id="room_size" name="room_size" min="50" step="10"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                           placeholder="e.g., 120">
                </div>
                
                <div>
                    <label for="floor" class="block text-sm font-semibold text-gray-700 mb-2">
                        Floor Number
                    </label>
                    <select id="floor" name="floor"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                        <option value="">Select Floor</option>
                        <option value="Ground">Ground Floor</option>
                        <option value="1">1st Floor</option>
                        <option value="2">2nd Floor</option>
                        <option value="3">3rd Floor</option>
                        <option value="4">4th Floor</option>
                        <option value="5">5th Floor</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Amenities Section -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-star text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Amenities & Features</h3>
                    <p class="text-sm text-gray-600">Select available amenities</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="amenities[]" value="wifi" class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">WiFi</span>
                </label>
                
                <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="amenities[]" value="ac" class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">AC</span>
                </label>
                
                <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="amenities[]" value="attached_bathroom" class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">Attached Bathroom</span>
                </label>
                
                <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="amenities[]" value="balcony" class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">Balcony</span>
                </label>
                
                <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="amenities[]" value="wardrobe" class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">Wardrobe</span>
                </label>
                
                <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="amenities[]" value="study_table" class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">Study Table</span>
                </label>
                
                <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="amenities[]" value="meals" class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">Meals Included</span>
                </label>
                
                <label class="flex items-center space-x-3 p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                    <input type="checkbox" name="amenities[]" value="laundry" class="text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">Laundry</span>
                </label>
            </div>
        </div>
        
        <!-- Description Section -->
        <div class="border-b border-gray-200 pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-align-left text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Description</h3>
                    <p class="text-sm text-gray-600">Detailed room description</p>
                </div>
            </div>
            
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Room Description <span class="text-red-500">*</span>
                </label>
                <textarea id="description" name="description" rows="5" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                          placeholder="Describe the room, its features, nearby facilities, and any special highlights..."></textarea>
            </div>
        </div>
        
        <!-- Images Section -->
        <div class="pb-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-images text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Room Images</h3>
                    <p class="text-sm text-gray-600">Upload room photos (max 5 images)</p>
                </div>
            </div>
            
            <div>
                <label for="images" class="block text-sm font-semibold text-gray-700 mb-2">
                    Upload Images
                </label>
                <input type="file" id="images" name="images[]" multiple accept="image/*"
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300">
                <p class="text-xs text-gray-500 mt-2">Supported formats: JPG, PNG, GIF. Max size: 5MB per image.</p>
            </div>
        </div>
        
        <!-- Form Actions -->
        <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
            <a href="<?= url('admin/rooms') ?>" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300 font-semibold">
                <i class="fas fa-times mr-2"></i>Cancel
            </a>
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-600 to-blue-600 text-white rounded-xl hover:from-green-700 hover:to-blue-700 transition-all duration-300 font-semibold shadow-lg">
                <i class="fas fa-save mr-2"></i>Create Room
            </button>
        </div>
    </form>
</div>

<!-- Form Enhancement Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const priceInput = document.getElementById('price');
    const securityDepositInput = document.getElementById('security_deposit');
    
    // Auto-calculate security deposit (typically 1-2 months rent)
    priceInput.addEventListener('input', function() {
        const price = parseInt(this.value);
        if (price && !securityDepositInput.value) {
            securityDepositInput.value = price; // 1 month rent as default
        }
    });
    
    // Form validation
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                isValid = false;
            } else {
                field.classList.remove('border-red-500');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields');
        }
    });
    
    // Image preview
    const imageInput = document.getElementById('images');
    imageInput.addEventListener('change', function() {
        const files = this.files;
        if (files.length > 5) {
            alert('Maximum 5 images allowed');
            this.value = '';
        }
    });
});
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/admin.php';
?>
