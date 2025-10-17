<?php
class AuthController extends Controller {
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->userModel = new User();
    }
    
    public function signup() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view('auth/signup', [
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Handle POST request
        $data = $this->sanitize($_POST);
        
        // Validate CSRF token
        if (!$this->validateCSRFToken($data['csrf_token'] ?? '')) {
            $this->view('auth/signup', [
                'error' => 'Invalid security token',
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Validate input
        $errors = $this->validate($data, [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'phone' => 'required'
        ]);
        
        if (!empty($errors)) {
            $this->view('auth/signup', [
                'errors' => $errors,
                'old' => $data,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Check if email already exists
        if ($this->userModel->findByEmail($data['email'])) {
            $this->view('auth/signup', [
                'error' => 'Email already exists',
                'old' => $data,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Create user
        try {
            $userId = $this->userModel->createUser([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'password' => $data['password'],
                'role' => 'user',
                'status' => 'active'
            ]);
            
            if ($userId) {
                $_SESSION['success'] = 'Account created successfully! Please login.';
                $this->redirect('/auth/login');
            } else {
                throw new Exception('Failed to create account');
            }
        } catch (Exception $e) {
            $this->view('auth/signup', [
                'error' => 'Failed to create account. Please try again.',
                'old' => $data,
                'csrf_token' => $this->generateCSRFToken()
            ]);
        }
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view('auth/login', [
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Handle POST request
        $data = $this->sanitize($_POST);
        
        // Validate CSRF token
        if (!$this->validateCSRFToken($data['csrf_token'] ?? '')) {
            $this->view('auth/login', [
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
            $this->view('auth/login', [
                'errors' => $errors,
                'old' => $data,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Authenticate user
        $user = $this->userModel->findByEmail($data['email']);
        
        if (!$user || !$this->userModel->verifyPassword($data['password'], $user['password_hash'])) {
            $this->view('auth/login', [
                'error' => 'Invalid email or password',
                'old' => $data,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        if ($user['status'] !== 'active') {
            $this->view('auth/login', [
                'error' => 'Your account is not active. Please contact support.',
                'old' => $data,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        
        // Redirect to intended page or home
        $redirect = $_SESSION['intended_url'] ?? '/';
        unset($_SESSION['intended_url']);
        $this->redirect($redirect);
    }
    
    public function logout() {
        // Clear user session
        unset($_SESSION['user_id']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_role']);
        
        $_SESSION['success'] = 'You have been logged out successfully.';
        $this->redirect('/');
    }
    
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->view('auth/forgot-password', [
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Handle POST request
        $data = $this->sanitize($_POST);
        
        // Validate CSRF token
        if (!$this->validateCSRFToken($data['csrf_token'] ?? '')) {
            $this->view('auth/forgot-password', [
                'error' => 'Invalid security token',
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Validate email
        $errors = $this->validate($data, [
            'email' => 'required|email'
        ]);
        
        if (!empty($errors)) {
            $this->view('auth/forgot-password', [
                'errors' => $errors,
                'old' => $data,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Check if user exists
        $user = $this->userModel->findByEmail($data['email']);
        
        if ($user) {
            // Generate reset token
            $token = $this->userModel->generateResetToken($data['email']);
            
            // Send reset email (implement email sending)
            $this->sendPasswordResetEmail($user['email'], $user['name'], $token);
        }
        
        // Always show success message for security
        $this->view('auth/forgot-password', [
            'success' => 'If your email exists in our system, you will receive a password reset link.',
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (empty($token)) {
                $this->redirect('/auth/forgot-password');
            }
            
            // Validate token
            $user = $this->userModel->validateResetToken($token);
            if (!$user) {
                $this->view('auth/reset-password', [
                    'error' => 'Invalid or expired reset token',
                    'csrf_token' => $this->generateCSRFToken()
                ]);
                return;
            }
            
            $this->view('auth/reset-password', [
                'token' => $token,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Handle POST request
        $data = $this->sanitize($_POST);
        
        // Validate CSRF token
        if (!$this->validateCSRFToken($data['csrf_token'] ?? '')) {
            $this->view('auth/reset-password', [
                'error' => 'Invalid security token',
                'token' => $token,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Validate input
        $errors = $this->validate($data, [
            'password' => 'required|min:6',
            'confirm_password' => 'required'
        ]);
        
        if ($data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match';
        }
        
        if (!empty($errors)) {
            $this->view('auth/reset-password', [
                'errors' => $errors,
                'token' => $token,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Validate token and update password
        $user = $this->userModel->validateResetToken($data['token']);
        if (!$user) {
            $this->view('auth/reset-password', [
                'error' => 'Invalid or expired reset token',
                'token' => $token,
                'csrf_token' => $this->generateCSRFToken()
            ]);
            return;
        }
        
        // Update password
        $this->userModel->updatePassword($user['id'], $data['password']);
        $this->userModel->clearResetToken($user['id']);
        
        $_SESSION['success'] = 'Password reset successfully! Please login with your new password.';
        $this->redirect('/auth/login');
    }
    
    private function sendPasswordResetEmail($email, $name, $token) {
        // Implement email sending logic here
        // You can use PHPMailer or similar library
        $resetLink = SITE_URL . "/auth/reset-password?token=" . $token;
        
        // For now, just log the reset link (in production, send actual email)
        error_log("Password reset link for {$email}: {$resetLink}");
    }
}
