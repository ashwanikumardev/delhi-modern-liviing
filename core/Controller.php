<?php
class Controller {
    protected $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    protected function view($view, $data = []) {
        extract($data);
        
        $viewFile = "views/{$view}.php";
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            throw new Exception("View {$view} not found");
        }
    }
    
    protected function render($view, $data = []) {
        // Alias for view method for compatibility
        $this->view($view, $data);
    }
    
    protected function redirect($url) {
        // Handle both absolute and relative URLs
        if (strpos($url, 'http') === 0) {
            header("Location: " . $url);
        } else {
            // Use url helper to ensure correct base path
            header("Location: " . url($url));
        }
        exit;
    }
    
    protected function json($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }
    
    protected function isAdmin() {
        return isset($_SESSION['admin_id']) && isset($_SESSION['admin_role']);
    }
    
    protected function requireAuth() {
        if (!$this->isAuthenticated()) {
            $this->redirect('/auth/login');
        }
    }
    
    protected function requireAdmin() {
        if (!$this->isAdmin()) {
            $this->redirect('/admin/login');
        }
    }
    
    protected function generateCSRFToken() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    protected function validateCSRFToken($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
    
    protected function validateCsrf() {
        $token = $_POST['csrf_token'] ?? '';
        if (!$this->validateCSRFToken($token)) {
            throw new Exception('Invalid CSRF token');
        }
    }
    
    protected function setFlash($type, $message) {
        $_SESSION['flash_' . $type] = $message;
    }
    
    protected function getFlash($type) {
        $message = $_SESSION['flash_' . $type] ?? null;
        unset($_SESSION['flash_' . $type]);
        return $message;
    }
    
    protected function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function getParam($key, $default = null) {
        return $_GET[$key] ?? $_POST[$key] ?? $default;
    }
    
    protected function logAdminAction($action, $details = '') {
        if (isset($_SESSION['admin_id'])) {
            // In a real app, you'd log this to database
            error_log("Admin Action: {$action} by admin {$_SESSION['admin_id']} - {$details}");
        }
    }
    
    protected function sanitize($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
    
    protected function validate($data, $rules) {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? '';
            $ruleList = explode('|', $rule);
            
            foreach ($ruleList as $singleRule) {
                if ($singleRule === 'required' && empty($value)) {
                    $errors[$field] = ucfirst($field) . ' is required';
                    break;
                }
                
                if (strpos($singleRule, 'min:') === 0 && strlen($value) < substr($singleRule, 4)) {
                    $errors[$field] = ucfirst($field) . ' must be at least ' . substr($singleRule, 4) . ' characters';
                    break;
                }
                
                if ($singleRule === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = ucfirst($field) . ' must be a valid email';
                    break;
                }
            }
        }
        
        return $errors;
    }
}
