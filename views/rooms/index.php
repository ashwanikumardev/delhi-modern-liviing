<?php
$title = 'Browse Rooms - Delhi Modern Living';
ob_start();
?>

<div class="bg-gray-50 min-h-screen">
    <!-- Page Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-3xl font-bold text-gray-900">Browse Rooms</h1>
            <p class="text-gray-600 mt-2">Find your perfect accommodation from our wide selection</p>
        </div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="lg:grid lg:grid-cols-4 lg:gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Filters</h2>
                    
                    <form action="<?= url('rooms') ?>" method="GET" id="filter-form">
                        <!-- Search -->
                        <div class="mb-6">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" id="search" name="search" 
                                   value="<?= htmlspecialchars($filters['search']) ?>"
                                   placeholder="Location, PG name..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        
                        <!-- Category -->
                        <div class="mb-6">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select id="category" name="category" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <option value="">All Categories</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['category'] ?>" <?= $filters['category'] === $cat['category'] ? 'selected' : '' ?>>
                                        <?= ucfirst($cat['category']) ?> (<?= $cat['count'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- City -->
                        <div class="mb-6">
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <select id="city" name="city" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <option value="">All Cities</option>
                                <?php foreach ($cities as $city): ?>
                                    <option value="<?= $city['city'] ?>" <?= $filters['city'] === $city['city'] ? 'selected' : '' ?>>
                                        <?= $city['city'] ?> (<?= $city['count'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Price Range -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price Range</label>
                            <div class="space-y-2">
                                <?php foreach ($price_ranges as $range): ?>
                                    <label class="flex items-center">
                                        <input type="radio" name="price_range" 
                                               value="<?= $range['min'] ?>-<?= $range['max'] ?>"
                                               <?= ($filters['min_price'] == $range['min'] && $filters['max_price'] == $range['max']) ? 'checked' : '' ?>
                                               class="text-primary-600 focus:ring-primary-500">
                                        <span class="ml-2 text-sm text-gray-700"><?= $range['label'] ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        
                        <!-- Availability -->
                        <div class="mb-6">
                            <label for="availability" class="block text-sm font-medium text-gray-700 mb-2">Availability</label>
                            <select id="availability" name="availability" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <option value="">All</option>
                                <option value="available" <?= $filters['availability'] === 'available' ? 'selected' : '' ?>>Available</option>
                                <option value="occupied" <?= $filters['availability'] === 'occupied' ? 'selected' : '' ?>>Occupied</option>
                            </select>
                        </div>
                        
                        <!-- Sort -->
                        <div class="mb-6">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                            <select id="sort" name="sort" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                                <option value="newest" <?= $filters['sort'] === 'newest' ? 'selected' : '' ?>>Newest First</option>
                                <option value="price_low" <?= $filters['sort'] === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                                <option value="price_high" <?= $filters['sort'] === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                                <option value="popular" <?= $filters['sort'] === 'popular' ? 'selected' : '' ?>>Most Popular</option>
                            </select>
                        </div>
                        
                        <div class="flex space-x-2">
                            <button type="submit" 
                                    class="flex-1 bg-primary-600 text-white hover:bg-primary-700 py-2 px-4 rounded-md font-medium transition duration-300">
                                Apply Filters
                            </button>
                            <a href="<?= url('rooms') ?>" 
                               class="flex-1 bg-gray-200 text-gray-700 hover:bg-gray-300 py-2 px-4 rounded-md font-medium text-center transition duration-300">
                                Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Rooms Grid -->
            <div class="lg:col-span-3 mt-8 lg:mt-0">
                <!-- Results Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">
                            <?= $pagination['total'] ?> rooms found
                        </h2>
                        <?php if (!empty($filters['search']) || !empty($filters['category']) || !empty($filters['city'])): ?>
                            <p class="text-sm text-gray-600 mt-1">
                                Filtered results
                                <?php if (!empty($filters['search'])): ?>
                                    for "<?= htmlspecialchars($filters['search']) ?>"
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Mobile Filter Toggle -->
                    <button type="button" class="lg:hidden bg-primary-600 text-white px-4 py-2 rounded-md" 
                            onclick="toggleMobileFilters()">
                        <i class="fas fa-filter mr-2"></i>Filters
                    </button>
                </div>
                
                <?php if (empty($rooms)): ?>
                    <!-- No Results -->
                    <div class="bg-white rounded-lg shadow-md p-8 text-center">
                        <div class="text-6xl text-gray-300 mb-4">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No rooms found</h3>
                        <p class="text-gray-600 mb-4">Try adjusting your filters or search criteria</p>
                        <a href="<?= url('rooms') ?>" class="bg-primary-600 text-white hover:bg-primary-700 px-6 py-2 rounded-md font-medium transition duration-300">
                            Clear Filters
                        </a>
                    </div>
                <?php else: ?>
                    <!-- Rooms Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <?php
                        require_once 'helpers/ImageHelper.php';
                        require_once 'helpers/AmenityHelper.php';
                        foreach ($rooms as $room):
                            $images = json_decode($room['images'] ?? '[]', true);
                            $amenities = json_decode($room['amenities'] ?? '[]', true);
                            // Use default amenities if none exist
                            if (empty($amenities)) {
                                $amenities = ['AC', 'WiFi', 'Furnished', 'Meals', 'Laundry', 'Security'];
                            }
                        ?>
                            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition duration-300 overflow-hidden">
                                <div class="relative">
                                    <img src="<?= ImageHelper::getImageUrl($images[0] ?? null) ?>"
                                         alt="<?= htmlspecialchars($room['title']) ?>"
                                         class="w-full h-48 object-cover"
                                         onerror="this.src='<?= url('assets/images/placeholder-room.jpg') ?>'">
                                    <div class="absolute top-3 left-3">
                                        <span class="bg-primary-600 text-white px-2 py-1 rounded text-xs font-semibold">
                                            <?= ucfirst($room['category']) ?>
                                        </span>
                                    </div>
                                    <div class="absolute top-3 right-3">
                                        <span class="<?= $room['availability_status'] === 'available' ? 'bg-green-500' : 'bg-red-500' ?> text-white px-2 py-1 rounded text-xs font-semibold">
                                            <?= ucfirst($room['availability_status']) ?>
                                        </span>
                                    </div>
                                    <?php if ($room['featured']): ?>
                                        <div class="absolute bottom-3 left-3">
                                            <span class="bg-yellow-500 text-white px-2 py-1 rounded text-xs font-semibold">
                                                <i class="fas fa-star mr-1"></i>Featured
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
                                        <?= htmlspecialchars($room['title']) ?>
                                    </h3>
                                    <div class="text-gray-600 text-sm mb-3">
                                        <?= AmenityHelper::renderLocation($room['address'], $room['city'], $room['pincode']) ?>
                                    </div>
                                    
                                    <!-- Amenities -->
                                    <div class="flex flex-wrap gap-1 mb-3">
                                        <?php foreach (array_slice($amenities, 0, 3) as $amenity): ?>
                                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs flex items-center">
                                                <i class="<?= AmenityHelper::getAmenityIcon($amenity) ?> <?= AmenityHelper::getAmenityColor($amenity) ?> mr-1 text-xs"></i>
                                                <?= htmlspecialchars($amenity) ?>
                                            </span>
                                        <?php endforeach; ?>
                                        <?php if (count($amenities) > 3): ?>
                                            <span class="bg-primary-50 text-primary-700 px-2 py-1 rounded text-xs font-semibold">
                                                +<?= count($amenities) - 3 ?> more
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Price and Action -->
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <span class="text-xl font-bold text-primary-600">
                                                ₹<?= number_format($room['price_per_month']) ?>
                                            </span>
                                            <span class="text-gray-600 text-sm">/month</span>
                                        </div>
                                        <a href="<?= url('rooms/' . $room['id']) ?>" 
                                           class="bg-primary-600 text-white hover:bg-primary-700 px-4 py-2 rounded-md text-sm font-medium transition duration-300">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <?php if ($pagination['last_page'] > 1): ?>
                        <div class="mt-8 flex justify-center">
                            <nav class="flex items-center space-x-2">
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] - 1])) ?>" 
                                       class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                        Previous
                                    </a>
                                <?php endif; ?>
                                
                                <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['last_page'], $pagination['current_page'] + 2); $i++): ?>
                                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                                       class="px-3 py-2 text-sm font-medium <?= $i === $pagination['current_page'] ? 'text-white bg-primary-600 border-primary-600' : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50' ?> border rounded-md">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] + 1])) ?>" 
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
    </div>
</div>

<!-- Mobile Filters Overlay -->
<div id="mobile-filters" class="lg:hidden fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-black bg-opacity-50" onclick="toggleMobileFilters()"></div>
    <div class="fixed inset-y-0 left-0 w-80 bg-white shadow-xl p-6 overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Filters</h2>
            <button onclick="toggleMobileFilters()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <!-- Same filter form as sidebar -->
        <div id="mobile-filter-content"></div>
    </div>
</div>

<script>
function toggleMobileFilters() {
    const overlay = document.getElementById('mobile-filters');
    const content = document.getElementById('mobile-filter-content');
    const sidebarForm = document.querySelector('.lg\\:col-span-1 form');
    
    if (overlay.classList.contains('hidden')) {
        // Show filters
        content.innerHTML = sidebarForm.outerHTML;
        overlay.classList.remove('hidden');
    } else {
        // Hide filters
        overlay.classList.add('hidden');
    }
}

// Handle price range radio buttons
document.addEventListener('change', function(e) {
    if (e.target.name === 'price_range') {
        const [min, max] = e.target.value.split('-');
        const form = e.target.closest('form');
        
        // Create hidden inputs for min and max price
        let minInput = form.querySelector('input[name="min_price"]');
        let maxInput = form.querySelector('input[name="max_price"]');
        
        if (!minInput) {
            minInput = document.createElement('input');
            minInput.type = 'hidden';
            minInput.name = 'min_price';
            form.appendChild(minInput);
        }
        
        if (!maxInput) {
            maxInput = document.createElement('input');
            maxInput.type = 'hidden';
            maxInput.name = 'max_price';
            form.appendChild(maxInput);
        }
        
        minInput.value = min;
        maxInput.value = max;
    }
});

// Auto-submit form on filter change
document.querySelectorAll('#filter-form select, #filter-form input[type="radio"]').forEach(element => {
    element.addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<?php
$content = ob_get_clean();
include 'views/layouts/app.php';
?>
