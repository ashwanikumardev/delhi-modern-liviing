<?php
class ProfileController extends Controller {
    private $userModel;
    private $orderModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
        $this->orderModel = new Order();
    }
    
    public function index() {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->find($userId);
        
        if (!$user) {
            $this->redirect('/auth/login');
            return;
        }
        
        // Get user statistics
        $stats = $this->getUserStats($userId);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->updateProfile($user);
            return;
        }
        
        $this->view('profile/index', [
            'user' => $user,
            'stats' => $stats,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    private function updateProfile($user) {
        $data = $this->sanitize($_POST);
        
        // Validate CSRF token
        if (!$this->validateCSRFToken($data['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            $this->redirect('/profile');
            return;
        }
        
        $action = $data['action'] ?? '';
        
        switch ($action) {
            case 'update_info':
                $this->updatePersonalInfo($user, $data);
                break;
            
            case 'change_password':
                $this->changePassword($user, $data);
                break;
            
            default:
                $_SESSION['error'] = 'Invalid action';
                $this->redirect('/profile');
        }
    }
    
    private function updatePersonalInfo($user, $data) {
        // Validate input
        $errors = $this->validate($data, [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'phone' => 'required'
        ]);
        
        if (!empty($errors)) {
            $_SESSION['error'] = 'Please fill all fields correctly.';
            $this->redirect('/profile');
            return;
        }
        
        // Check if email is already taken by another user
        if ($data['email'] !== $user['email']) {
            $existingUser = $this->userModel->findByEmail($data['email']);
            if ($existingUser && $existingUser['id'] !== $user['id']) {
                $_SESSION['error'] = 'Email address is already taken.';
                $this->redirect('/profile');
                return;
            }
        }
        
        try {
            // Update user information
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone']
            ];
            
            $this->userModel->update($user['id'], $updateData);
            
            // Update session data
            $_SESSION['user_name'] = $data['name'];
            $_SESSION['user_email'] = $data['email'];
            
            $_SESSION['success'] = 'Profile updated successfully.';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Failed to update profile. Please try again.';
        }
        
        $this->redirect('/profile');
    }
    
    private function changePassword($user, $data) {
        // Validate input
        $errors = $this->validate($data, [
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required'
        ]);
        
        if (!empty($errors)) {
            $_SESSION['error'] = 'Please fill all password fields correctly.';
            $this->redirect('/profile');
            return;
        }
        
        // Verify current password
        if (!$this->userModel->verifyPassword($data['current_password'], $user['password_hash'])) {
            $_SESSION['error'] = 'Current password is incorrect.';
            $this->redirect('/profile');
            return;
        }
        
        // Check if new passwords match
        if ($data['new_password'] !== $data['confirm_password']) {
            $_SESSION['error'] = 'New passwords do not match.';
            $this->redirect('/profile');
            return;
        }
        
        // Check if new password is different from current
        if ($this->userModel->verifyPassword($data['new_password'], $user['password_hash'])) {
            $_SESSION['error'] = 'New password must be different from current password.';
            $this->redirect('/profile');
            return;
        }
        
        try {
            // Update password
            $this->userModel->updatePassword($user['id'], $data['new_password']);
            
            $_SESSION['success'] = 'Password changed successfully.';
            
        } catch (Exception $e) {
            $_SESSION['error'] = 'Failed to change password. Please try again.';
        }
        
        $this->redirect('/profile');
    }
    
    private function getUserStats($userId) {
        $stats = [];
        
        // Total bookings
        $stats['total_bookings'] = $this->orderModel->count(['user_id' => $userId]);
        
        // Active bookings
        $stats['active_bookings'] = $this->orderModel->count([
            'user_id' => $userId,
            'booking_status' => 'confirmed'
        ]);
        
        // Total spent
        $sql = "SELECT SUM(total_amount) as total_spent 
                FROM orders 
                WHERE user_id = ? AND payment_status = 'paid'";
        $result = $this->db->fetch($sql, [$userId]);
        $stats['total_spent'] = $result['total_spent'] ?? 0;
        
        // Member since
        $user = $this->userModel->find($userId);
        $stats['member_since'] = $user['created_at'];
        
        // Recent bookings
        $sql = "SELECT o.*, r.title as room_title, r.address as room_address
                FROM orders o
                JOIN rooms r ON o.room_id = r.id
                WHERE o.user_id = ?
                ORDER BY o.created_at DESC
                LIMIT 3";
        $stats['recent_bookings'] = $this->db->fetchAll($sql, [$userId]);
        
        return $stats;
    }
}
