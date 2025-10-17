<?php
$title = 'User Management - Admin Dashboard';
$page_title = 'User Management';
ob_start();
?>

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Users</h1>
        <p class="text-gray-600">Manage user accounts and permissions</p>
    </div>
    <div class="flex space-x-3">
        <a href="<?= url('admin/users/create') ?>" class="bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 rounded-lg font-medium transition duration-300">
            <i class="fas fa-plus mr-2"></i>Add User
        </a>
        <button onclick="exportUsers()" class="bg-green-600 text-white hover:bg-green-700 px-4 py-2 rounded-lg font-medium transition duration-300">
            <i class="fas fa-download mr-2"></i>Export
        </button>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" id="search" name="search" 
                   value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                   placeholder="Name or email..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
        </div>
        
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="status" name="status" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Status</option>
                <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="blocked" <?= ($filters['status'] ?? '') === 'blocked' ? 'selected' : '' ?>>Blocked</option>
                <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
            </select>
        </div>
        
        <div>
            <label for="role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select id="role" name="role" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Roles</option>
                <option value="user" <?= ($filters['role'] ?? '') === 'user' ? 'selected' : '' ?>>User</option>
                <option value="admin" <?= ($filters['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="super_admin" <?= ($filters['role'] ?? '') === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
            </select>
        </div>
        
        <div class="flex items-end space-x-2">
            <button type="submit" class="bg-primary-600 text-white hover:bg-primary-700 px-4 py-2 rounded-md font-medium transition duration-300">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="<?= url('admin/users') ?>" class="bg-gray-200 text-gray-700 hover:bg-gray-300 px-4 py-2 rounded-md font-medium transition duration-300">
                Clear
            </a>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bookings</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-users text-4xl mb-4"></i>
                            <p>No users found</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr class="hover:bg-gray-50" id="user-row-<?= $user['id'] ?>">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-primary-500 flex items-center justify-center text-white font-medium">
                                        <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($user['name']) ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?= htmlspecialchars($user['email']) ?>
                                        </div>
                                        <?php if (!empty($user['phone'])): ?>
                                            <div class="text-xs text-gray-400">
                                                <?= htmlspecialchars($user['phone']) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?= $user['role'] === 'super_admin' ? 'bg-purple-100 text-purple-800' : 
                                        ($user['role'] === 'admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') ?>">
                                    <?= ucfirst(str_replace('_', ' ', $user['role'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                                        ($user['status'] === 'blocked' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                    <?= ucfirst($user['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?= $user['booking_count'] ?? 0 ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('M d, Y', strtotime($user['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="<?= url('admin/users/' . $user['id']) ?>" 
                                       class="text-primary-600 hover:text-primary-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <?php if ($user['role'] !== 'super_admin'): ?>
                                        <div class="relative inline-block text-left">
                                            <button onclick="toggleUserActions(<?= $user['id'] ?>)" 
                                                    class="text-gray-600 hover:text-gray-900">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            
                                            <div id="user-actions-<?= $user['id'] ?>" 
                                                 class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-10">
                                                <div class="py-1">
                                                    <?php if ($user['status'] === 'active'): ?>
                                                        <button onclick="updateUserStatus(<?= $user['id'] ?>, 'blocked')" 
                                                                class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                            <i class="fas fa-ban mr-2"></i>Block User
                                                        </button>
                                                    <?php else: ?>
                                                        <button onclick="updateUserStatus(<?= $user['id'] ?>, 'active')" 
                                                                class="block w-full text-left px-4 py-2 text-sm text-green-700 hover:bg-green-50">
                                                            <i class="fas fa-check mr-2"></i>Activate User
                                                        </button>
                                                    <?php endif; ?>
                                                    
                                                    <button onclick="deleteUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['name']) ?>')" 
                                                            class="block w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50">
                                                        <i class="fas fa-trash mr-2"></i>Delete User
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($pagination['last_page'] > 1): ?>
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            <div class="flex items-center justify-between">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($pagination['current_page'] > 1): ?>
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] - 1])) ?>" 
                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                    <?php endif; ?>
                    <?php if ($pagination['current_page'] < $pagination['last_page']): ?>
                        <a href="?<?= http_build_query(array_merge($_GET, ['page' => $pagination['current_page'] + 1])) ?>" 
                           class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing <span class="font-medium"><?= (($pagination['current_page'] - 1) * $pagination['per_page']) + 1 ?></span>
                            to <span class="font-medium"><?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total']) ?></span>
                            of <span class="font-medium"><?= $pagination['total'] ?></span> results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                            <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['last_page'], $pagination['current_page'] + 2); $i++): ?>
                                <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>" 
                                   class="relative inline-flex items-center px-4 py-2 border text-sm font-medium
                                   <?= $i === $pagination['current_page'] ? 
                                       'z-10 bg-primary-50 border-primary-500 text-primary-600' : 
                                       'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function toggleUserActions(userId) {
    const menu = document.getElementById(`user-actions-${userId}`);
    
    // Close all other menus
    document.querySelectorAll('[id^="user-actions-"]').forEach(m => {
        if (m !== menu) m.classList.add('hidden');
    });
    
    menu.classList.toggle('hidden');
}

function updateUserStatus(userId, status) {
    if (!confirm(`Are you sure you want to ${status} this user?`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('status', status);
    formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');
    
    fetch('/admin/users/update-status', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Failed to update user status');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

function deleteUser(userId, userName) {
    if (!confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) {
        return;
    }
    
    const formData = new FormData();
    formData.append('user_id', userId);
    formData.append('csrf_token', '<?= $_SESSION['csrf_token'] ?? '' ?>');
    
    fetch('/admin/users/delete', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`user-row-${userId}`).remove();
            alert('User deleted successfully');
        } else {
            alert(data.message || 'Failed to delete user');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

function exportUsers() {
    // Show export options
    const format = prompt('Choose export format:\n1. CSV\n2. PDF\n\nEnter 1 or 2:', '1');

    if (format === '1' || format === 'csv') {
        window.open('<?= url('admin/users/export?format=csv') ?>', '_blank');
    } else if (format === '2' || format === 'pdf') {
        window.open('<?= url('admin/users/export?format=pdf') ?>', '_blank');
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('[onclick^="toggleUserActions"]')) {
        document.querySelectorAll('[id^="user-actions-"]').forEach(menu => {
            menu.classList.add('hidden');
        });
    }
});
</script>

<?php
$content = ob_get_clean();
include 'views/layouts/admin.php';
?>
