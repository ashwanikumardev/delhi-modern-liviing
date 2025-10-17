<?php
class AdminAuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // If already logged in as admin, redirect to dashboard
            if ($this->isAdmin()) {
                $this->redirect('/admin');
            }
            
            $this->view('admin/auth/login', [
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Handle POST request
        $data = $this->sanitize($_POST);
        
        // Validate CSRF token
        if (!$this->validateCSRFToken($data['csrf_token'] ?? '')) {
            $this->view('admin/auth/login', [
                'error' => 'Invalid security token',
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Validate input
        $errors = $this->validate($data, [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if (!empty($errors)) {
            $this->view('admin/auth/login', [
                'errors' => $errors,
                'old' => $data,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Authenticate admin
        $user = $this->userModel->findByEmail($data['email']);
        
        if (!$user || !$this->userModel->verifyPassword($data['password'], $user['password_hash'])) {
            $this->view('admin/auth/login', [
                'error' => 'Invalid email or password',
                'old' => $data,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Check if user is admin
        if (!in_array($user['role'], ['admin', 'super_admin'])) {
            $this->view('admin/auth/login', [
                'error' => 'Access denied. Admin privileges required.',
                'old' => $data,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        if ($user['status'] !== 'active') {
            $this->view('admin/auth/login', [
                'error' => 'Your account is not active. Please contact support.',
                'old' => $data,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Set admin session
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_name'] = $user['name'];
        $_SESSION['admin_email'] = $user['email'];
        $_SESSION['admin_role'] = $user['role'];
        
        // Log admin login
        $this->logAdminAction('admin_login', $user['id'], null, null, [
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
        ]);
        
        $this->redirect('/admin');
    }
    
    public function logout() {
        // Log admin logout
        if (isset($_SESSION['admin_id'])) {
            $this->logAdminAction('admin_logout', $_SESSION['admin_id'], null, null, [
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? ''
            ]);
        }
        
        // Clear admin session
        unset($_SESSION['admin_id']);
        unset($_SESSION['admin_name']);
        unset($_SESSION['admin_email']);
        unset($_SESSION['admin_role']);
        
        $_SESSION['success'] = 'You have been logged out successfully.';
        $this->redirect('/admin/login');
    }
    
    protected function logAdminAction($action, $details = '') {
        // Call parent method for basic logging
        parent::logAdminAction($action, $details);
        
        // Additional detailed logging for admin actions
        if (isset($_SESSION['admin_id'])) {
            try {
                // Check if audit_logs table exists, if not just use basic logging
                $tableExists = $this->db->query("SHOW TABLES LIKE 'audit_logs'")->fetch();
                if ($tableExists) {
                    $sql = "INSERT INTO audit_logs (action, performed_by_admin_id, details, ip_address, user_agent, created_at) 
                            VALUES (?, ?, ?, ?, ?, NOW())";
                    
                    $this->db->query($sql, [
                        $action,
                        $_SESSION['admin_id'],
                        $details,
                        $_SERVER['REMOTE_ADDR'] ?? null,
                        $_SERVER['HTTP_USER_AGENT'] ?? null
                    ]);
                }
            } catch (Exception $e) {
                // Log error but don't break the flow
                error_log("Failed to log admin action: " . $e->getMessage());
            }
        }
    }
}
