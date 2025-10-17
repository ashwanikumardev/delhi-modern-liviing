<?php
$page_title = 'Support Tickets';
ob_start();
?>

<!-- Modern Ticket Management Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                Support Tickets
            </h1>
            <p class="text-gray-600 mt-2">Manage customer support requests</p>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Total Tickets</p>
                <p class="text-3xl font-bold text-gray-900"><?= number_format($stats['total'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-ticket-alt text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Open</p>
                <p class="text-3xl font-bold text-yellow-600"><?= number_format($stats['open'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-folder-open text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">In Progress</p>
                <p class="text-3xl font-bold text-blue-600"><?= number_format($stats['in_progress'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-spinner text-white text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-600 mb-1">Closed</p>
                <p class="text-3xl font-bold text-green-600"><?= number_format($stats['closed'] ?? 0) ?></p>
            </div>
            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-check-circle text-white text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-100">
    <form method="GET" action="<?= url('admin/tickets') ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Search</label>
            <input type="text" name="search" value="<?= htmlspecialchars($filters['search'] ?? '') ?>" 
                   placeholder="Search tickets..." 
                   class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">All Status</option>
                <option value="open" <?= ($filters['status'] ?? '') === 'open' ? 'selected' : '' ?>>Open</option>
                <option value="in_progress" <?= ($filters['status'] ?? '') === 'in_progress' ? 'selected' : '' ?>>In Progress</option>
                <option value="closed" <?= ($filters['status'] ?? '') === 'closed' ? 'selected' : '' ?>>Closed</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Priority</label>
            <select name="priority" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">All Priorities</option>
                <option value="low" <?= ($filters['priority'] ?? '') === 'low' ? 'selected' : '' ?>>Low</option>
                <option value="medium" <?= ($filters['priority'] ?? '') === 'medium' ? 'selected' : '' ?>>Medium</option>
                <option value="high" <?= ($filters['priority'] ?? '') === 'high' ? 'selected' : '' ?>>High</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-300 font-semibold">
                <i class="fas fa-search mr-2"></i>Filter
            </button>
            <a href="<?= url('admin/tickets') ?>" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 transition-all duration-300">
                <i class="fas fa-redo"></i>
            </a>
        </div>
    </form>
</div>

<!-- Tickets Table -->
<div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Ticket ID</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">User</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Subject</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Priority</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Replies</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (!empty($tickets)): ?>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="font-bold text-gray-900">#<?= $ticket['id'] ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900"><?= htmlspecialchars($ticket['user_name']) ?></p>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars($ticket['user_email']) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900"><?= htmlspecialchars($ticket['subject']) ?></p>
                                <p class="text-sm text-gray-500"><?= htmlspecialchars(substr($ticket['message'], 0, 50)) ?>...</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    <?= $ticket['priority'] === 'high' ? 'bg-red-100 text-red-700' : 
                                        ($ticket['priority'] === 'medium' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700') ?>">
                                    <?= ucfirst($ticket['priority']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold 
                                    <?= $ticket['status'] === 'closed' ? 'bg-green-100 text-green-700' : 
                                        ($ticket['status'] === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'bg-yellow-100 text-yellow-700') ?>">
                                    <i class="fas fa-circle text-xs mr-1"></i>
                                    <?= ucfirst(str_replace('_', ' ', $ticket['status'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-semibold">
                                    <i class="fas fa-comments mr-1"></i>
                                    <?= $ticket['reply_count'] ?? 0 ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-900"><?= date('M d, Y', strtotime($ticket['created_at'])) ?></p>
                                <p class="text-xs text-gray-500"><?= date('h:i A', strtotime($ticket['created_at'])) ?></p>
                            </td>
                            <td class="px-6 py-4">
                                <a href="<?= url('admin/tickets/' . $ticket['id'] . '/view') ?>" class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition-colors text-sm font-semibold">
                                    <i class="fas fa-eye mr-1"></i>View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <i class="fas fa-ticket-alt text-gray-300 text-5xl mb-4"></i>
                            <p class="text-gray-500 text-lg">No support tickets found</p>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    Showing page <?= $currentPage ?> of <?= $totalPages ?>
                </p>
                <div class="flex gap-2">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= url('admin/tickets?page=' . ($currentPage - 1)) ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $currentPage - 2); $i <= min($totalPages, $currentPage + 2); $i++): ?>
                        <a href="<?= url('admin/tickets?page=' . $i) ?>" class="px-4 py-2 <?= $i === $currentPage ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50' ?> rounded-lg transition-colors">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= url('admin/tickets?page=' . ($currentPage + 1)) ?>" class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'views/layouts/admin.php';
?>

