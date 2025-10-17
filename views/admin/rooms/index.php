<?php
$page_title = 'Room Management';
ob_start();
?>

<!-- Modern Room Management Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">
                Room Management
            </h1>
            <p class="text-gray-600 mt-2">Manage all PG rooms and listings</p>
        </div>
        <div class="flex gap-3">
            <a href="<?= url('admin/rooms/create') ?>" class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 flex items-center gap-2 shadow-lg">
                <i class="fas fa-plus"></i>
                <span class="font-semibold">Add New Room</span>
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Rooms</p>
                <p class="text-3xl font-bold text-gray-900"><?= number_format($stats['total'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-bed text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Active</p>
                <p class="text-3xl font-bold text-green-600"><?= number_format($stats['active'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-check-circle text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Inactive</p>
                <p class="text-3xl font-bold text-red-600"><?= number_format($stats['inactive'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-times-circle text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Available</p>
                <p class="text-3xl font-bold text-blue-600"><?= number_format($stats['available'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-door-open text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Occupied</p>
                <p class="text-3xl font-bold text-yellow-600"><?= number_format($stats['occupied'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-door-closed text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-lg p-4 lg:p-6 mb-6 border border-gray-100">
    <form method="GET" action="<?= url('admin/rooms') ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                   placeholder="Search rooms..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-base">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
            <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-base">
                <option value="">All Categories</option>
                <option value="single" <?= ($filters['category'] ?? '') === 'single' ? 'selected' : '' ?>>Single</option>
                <option value="double" <?= ($filters['category'] ?? '') === 'double' ? 'selected' : '' ?>>Double</option>
                <option value="shared" <?= ($filters['category'] ?? '') === 'shared' ? 'selected' : '' ?>>Shared</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent text-base">
                <option value="">All Status</option>
                <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($filters['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>
        <div class="flex items-end gap-2 sm:col-span-2 lg:col-span-1">
            <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300 font-semibold text-sm lg:text-base">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="<?= url('admin/rooms') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Rooms Table -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
    <!-- Desktop Table -->
    <div class="hidden lg:block admin-table-container">
        <table class="min-w-full divide-y divide-gray-200 admin-table">
            <thead class="bg-gradient-to-r from-green-50 to-emerald-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Room</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Availability</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($rooms)): ?>
                    <?php foreach ($rooms as $room): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <?php
                                    require_once 'helpers/ImageHelper.php';
                                    $images = json_decode($room['images'] ?? '[]', true);
                                    $firstImage = !empty($images) ? $images[0] : null;
                                    ?>
                                    <?php if ($firstImage): ?>
                                        <img src="<?= ImageHelper::getImageUrl($firstImage) ?>" alt="<?= htmlspecialchars($room['title'] ?? '') ?>" class="w-16 h-16 rounded-lg object-cover">
                                    <?php else: ?>
                                        <img src="<?= url('assets/images/placeholder-room.jpg') ?>" alt="No image" class="w-16 h-16 rounded-lg object-cover">
                                    <?php endif; ?>
                                    <div>
                                        <p class="font-semibold text-gray-900"><?= htmlspecialchars($room['title'] ?? '') ?></p>
                                        <p class="text-sm text-gray-500"><?= htmlspecialchars(substr($room['description'] ?? '', 0, 50)) ?>...</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                    <?= ucfirst($room['category'] ?? '') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">₹<?= number_format($room['price_per_month'] ?? 0) ?></p>
                                <p class="text-xs text-gray-500">per month</p>
                            </td>
                            <td class="px-6 py-4">
                                <?php require_once 'helpers/AmenityHelper.php'; ?>
                                <p class="text-sm text-gray-900">456 Cyber City, Gurgaon</p>
                                <p class="text-xs text-gray-500 italic">Map integration coming soon</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?= ($room['status'] ?? '') === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    <?= ucfirst($room['status'] ?? '') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?= ($room['availability_status'] ?? '') === 'available' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' ?>">
                                    <?= ucfirst($room['availability_status'] ?? '') ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="<?= url('admin/rooms/' . ($room['id'] ?? '') . '/edit') ?>" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-semibold">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteRoom(<?= $room['id'] ?? '' ?>)" class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-semibold">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <i class="fas fa-bed text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg">No rooms found</p>
                            <a href="<?= url('admin/rooms/create') ?>" class="inline-block mt-4 px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300">
                                <i class="fas fa-plus mr-2"></i>Add Your First Room
                            </a>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards View -->
    <div class="lg:hidden">
        <?php if (!empty($rooms)): ?>
            <div class="p-4 space-y-4">
                <?php foreach ($rooms as $room): ?>
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <div class="flex items-start gap-3 mb-3">
                            <?php
                            require_once 'helpers/ImageHelper.php';
                            $images = json_decode($room['images'] ?? '[]', true);
                            $firstImage = !empty($images) ? $images[0] : null;
                            ?>
                            <?php if ($firstImage): ?>
                                <img src="<?= ImageHelper::getImageUrl($firstImage) ?>" alt="<?= htmlspecialchars($room['title'] ?? '') ?>" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                            <?php else: ?>
                                <img src="<?= url('assets/images/placeholder-room.jpg') ?>" alt="No image" class="w-16 h-16 rounded-lg object-cover flex-shrink-0">
                            <?php endif; ?>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 text-sm mb-1"><?= htmlspecialchars($room['title'] ?? '') ?></h3>
                                <p class="text-xs text-gray-500 mb-2"><?= htmlspecialchars(substr($room['description'] ?? '', 0, 60)) ?>...</p>
                                <div class="flex flex-wrap gap-2 text-xs">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                        <?= ucfirst($room['category'] ?? '') ?>
                                    </span>
                                    <span class="px-2 py-1 rounded-full text-xs font-bold <?= ($room['status'] ?? '') === 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' ?>">
                                        <?= ucfirst($room['status'] ?? '') ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-sm mb-3">
                            <div>
                                <span class="text-gray-500 text-xs">Price:</span>
                                <p class="font-bold text-gray-900">₹<?= number_format($room['price_per_month'] ?? 0) ?>/month</p>
                            </div>
                            <div>
                                <span class="text-gray-500 text-xs">Location:</span>
                                <p class="text-gray-900 text-xs">456 Cyber City, Gurgaon</p>
                            </div>
                        </div>

                        <div class="flex gap-2">
                            <a href="<?= url('admin/rooms/' . ($room['id'] ?? '') . '/edit') ?>" class="flex-1 px-3 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors text-sm font-semibold text-center">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <button onclick="deleteRoom(<?= $room['id'] ?? '' ?>)" class="flex-1 px-3 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-colors text-sm font-semibold">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="p-8 text-center">
                <i class="fas fa-bed text-gray-300 text-5xl mb-4"></i>
                <p class="text-gray-500 text-lg mb-4">No rooms found</p>
                <a href="<?= url('admin/rooms/create') ?>" class="inline-block px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition-all duration-300">
                    <i class="fas fa-plus mr-2"></i>Add Your First Room
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="px-4 lg:px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <p class="text-sm text-gray-600 text-center sm:text-left">
                    Showing page <?= $currentPage ?> of <?= $totalPages ?>
                </p>
                <div class="flex gap-1 justify-center sm:justify-end">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= url('admin/rooms?page=' . ($currentPage - 1)) ?>" class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>

                    <?php
                    $start = max(1, $currentPage - 1);
                    $end = min($totalPages, $currentPage + 1);
                    if ($currentPage <= 2) $end = min($totalPages, 3);
                    if ($currentPage >= $totalPages - 1) $start = max(1, $totalPages - 2);
                    ?>

                    <?php for ($i = $start; $i <= $end; $i++): ?>
                        <a href="<?= url('admin/rooms?page=' . $i) ?>" class="px-3 py-2 text-sm <?= $i === $currentPage ? 'bg-green-600 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50' ?> rounded-lg transition-colors">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= url('admin/rooms?page=' . ($currentPage + 1)) ?>" class="px-3 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function deleteRoom(id) {
    if (confirm('Are you sure you want to delete this room?')) {
        const formData = new FormData();
        formData.append('csrf_token', '<?= $csrf_token ?? '' ?>');

        fetch('<?= url('admin/rooms/') ?>' + id + '/delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error deleting room: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting room');
        });
    }
}
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/admin.php';
?>

