<?php
class AdminUserController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    public function index() {
        $this->requireAdmin();
        
        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $role = $_GET['role'] ?? '';
        
        $filters = array_filter([
            'search' => $search,
            'status' => $status,
            'role' => $role
        ]);
        
        $result = $this->userModel->getUsersWithBookingCount($page, ADMIN_ITEMS_PER_PAGE, $search);
        
        $this->view('admin/users/index', [
            'users' => $result['users'],
            'pagination' => [
                'current_page' => $result['current_page'],
                'last_page' => $result['last_page'],
                'total' => $result['total'],
                'per_page' => $result['per_page']
            ],
            'filters' => $filters,
            'page_title' => 'User Management'
        ]);
    }
    
    public function show($id) {
        $this->requireAdmin();
        
        $user = $this->userModel->find($id);
        if (!$user) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }
        
        // Get user's booking history
        $orderModel = new Order();
        $bookings = $orderModel->getUserOrders($id, 1, 20);
        
        $this->view('admin/users/show', [
            'user' => $user,
            'bookings' => $bookings['orders'],
            'page_title' => 'User Details'
        ]);
    }
    
    public function create() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreate();
            return;
        }
        
        $this->view('admin/users/create', [
            'page_title' => 'Create User',
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    public function edit($id) {
        $this->requireAdmin();
        
        $user = $this->userModel->find($id);
        if (!$user) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleEdit($id);
            return;
        }
        
        $this->view('admin/users/edit', [
            'user' => $user,
            'page_title' => 'Edit User',
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    private function handleCreate() {
        $this->validateCsrf();
        
        $data = $this->sanitize($_POST);
        
        // Validate required fields
        $required = ['name', 'email', 'password', 'phone'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $_SESSION['flash_error'] = ucfirst($field) . ' is required';
                $this->redirect('/admin/users/create');
                return;
            }
        }
        
        // Validate email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Invalid email address';
            $this->redirect('/admin/users/create');
            return;
        }
        
        // Check if email already exists
        if ($this->userModel->findByEmail($data['email'])) {
            $_SESSION['flash_error'] = 'Email already exists';
            $this->redirect('/admin/users/create');
            return;
        }
        
        try {
            $userId = $this->userModel->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                'phone' => $data['phone'],
                'role' => $data['role'] ?? 'user',
                'status' => $data['status'] ?? 'active'
            ]);
            
            $this->logAdminAction('user_create', 'Created user: ' . $data['email']);
            
            $_SESSION['flash_success'] = 'User created successfully';
            $this->redirect('/admin/users');
        } catch (Exception $e) {
            $_SESSION['flash_error'] = 'Failed to create user';
            $this->redirect('/admin/users/create');
        }
    }
    
    private function handleEdit($id) {
        $this->validateCsrf();
        
        $data = $this->sanitize($_POST);
        $user = $this->userModel->find($id);
        
        if (!$user) {
            $_SESSION['flash_error'] = 'User not found';
            $this->redirect('/admin/users');
            return;
        }
        
        // Validate required fields
        $required = ['name', 'email', 'phone'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $_SESSION['flash_error'] = ucfirst($field) . ' is required';
                $this->redirect("/admin/users/$id/edit");
                return;
            }
        }
        
        // Validate email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Invalid email address';
            $this->redirect("/admin/users/$id/edit");
            return;
        }
        
        // Check if email already exists for another user
        $existingUser = $this->userModel->findByEmail($data['email']);
        if ($existingUser && $existingUser['id'] != $id) {
            $_SESSION['flash_error'] = 'Email already exists';
            $this->redirect("/admin/users/$id/edit");
            return;
        }
        
        try {
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'role' => $data['role'] ?? $user['role'],
                'status' => $data['status'] ?? $user['status']
            ];
            
            // Update password if provided
            if (!empty($data['password'])) {
                $updateData['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
            $this->userModel->update($id, $updateData);
            
            $this->logAdminAction('user_update', 'Updated user ID: ' . $id);
            
            $_SESSION['flash_success'] = 'User updated successfully';
            $this->redirect('/admin/users');
        } catch (Exception $e) {
            $_SESSION['flash_error'] = 'Failed to update user';
            $this->redirect("/admin/users/$id/edit");
        }
    }
    
    public function updateStatus() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request method'], 405);
            return;
        }
        
        $data = $this->sanitize($_POST);
        
        if (!$this->validateCSRFToken($data['csrf_token'] ?? '')) {
            $this->json(['success' => false, 'message' => 'Invalid security token'], 403);
            return;
        }
        
        $userId = $data['user_id'] ?? '';
        $status = $data['status'] ?? '';
        
        if (!$userId || !in_array($status, ['active', 'blocked', 'pending'])) {
            $this->json(['success' => false, 'message' => 'Invalid parameters'], 400);
            return;
        }
        
        try {
            $user = $this->userModel->find($userId);
            if (!$user) {
                $this->json(['success' => false, 'message' => 'User not found'], 404);
                return;
            }
            
            // Prevent blocking super admins
            if ($user['role'] === 'super_admin' && $status === 'blocked') {
                $this->json(['success' => false, 'message' => 'Cannot block super admin'], 403);
                return;
            }
            
            $this->userModel->update($userId, ['status' => $status]);
            
            // Log admin action
            $this->logAdminAction('user_status_update', 'Updated user ID ' . $userId . ' status to: ' . $status);
            
            $this->json(['success' => true, 'message' => 'User status updated successfully']);
            
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Failed to update user status'], 500);
        }
    }
    
    public function delete() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request method'], 405);
            return;
        }
        
        $data = $this->sanitize($_POST);
        
        if (!$this->validateCSRFToken($data['csrf_token'] ?? '')) {
            $this->json(['success' => false, 'message' => 'Invalid security token'], 403);
            return;
        }
        
        $userId = $data['user_id'] ?? '';
        
        if (!$userId) {
            $this->json(['success' => false, 'message' => 'User ID is required'], 400);
            return;
        }
        
        try {
            $user = $this->userModel->find($userId);
            if (!$user) {
                $this->json(['success' => false, 'message' => 'User not found'], 404);
                return;
            }
            
            // Prevent deleting super admins
            if ($user['role'] === 'super_admin') {
                $this->json(['success' => false, 'message' => 'Cannot delete super admin'], 403);
                return;
            }
            
            // Check if user has active bookings
            $orderModel = new Order();
            $activeBookings = $orderModel->count([
                'user_id' => $userId,
                'booking_status' => 'confirmed'
            ]);
            
            if ($activeBookings > 0) {
                $this->json(['success' => false, 'message' => 'Cannot delete user with active bookings'], 400);
                return;
            }
            
            $this->userModel->delete($userId);
            
            // Log admin action
            $this->logAdminAction('user_delete', 'Deleted user ID: ' . $userId);
            
            $this->json(['success' => true, 'message' => 'User deleted successfully']);
            
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Failed to delete user'], 500);
        }
    }

    public function export() {
        $this->requireAuth();
        $this->requireAdmin();

        $format = $_GET['format'] ?? 'csv';
        $users = $this->userModel->findAll([], 'created_at DESC');

        if ($format === 'csv') {
            $this->exportCSV($users);
        } else {
            $this->exportPDF($users);
        }
    }

    private function exportCSV($users) {
        $filename = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-cache, must-revalidate');

        $output = fopen('php://output', 'w');

        // CSV Headers
        fputcsv($output, [
            'ID', 'Name', 'Email', 'Phone', 'Role', 'Status',
            'Email Verified', 'Created At', 'Last Login'
        ]);

        // CSV Data
        foreach ($users as $user) {
            fputcsv($output, [
                $user['id'],
                $user['name'],
                $user['email'],
                $user['phone'] ?? '',
                ucfirst($user['role']),
                ucfirst($user['status']),
                $user['email_verified'] ? 'Yes' : 'No',
                date('Y-m-d H:i:s', strtotime($user['created_at'])),
                $user['last_login'] ? date('Y-m-d H:i:s', strtotime($user['last_login'])) : 'Never'
            ]);
        }

        fclose($output);
        exit;
    }

    private function exportPDF($users) {
        // For now, redirect to CSV export
        // TODO: Implement PDF export with TCPDF or similar library
        $this->exportCSV($users);
    }

}
