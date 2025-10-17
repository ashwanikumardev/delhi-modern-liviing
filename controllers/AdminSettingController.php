<?php
class AdminSettingController extends Controller {
    
    public function index() {
        $this->requireAdmin();
        
        // Get all settings
        $sql = "SELECT * FROM settings ORDER BY setting_key";
        $settings = $this->db->query($sql)->fetchAll();
        
        // Group settings by category
        $groupedSettings = [];
        foreach ($settings as $setting) {
            $category = $this->getSettingCategory($setting['setting_key']);
            $groupedSettings[$category][] = $setting;
        }
        
        $this->render('admin/settings/index', [
            'settings' => $groupedSettings
        ]);
    }
    
    public function update() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/settings');
        }
        
        $settings = $_POST['settings'] ?? [];
        $updated = 0;
        
        foreach ($settings as $key => $value) {
            // Sanitize and validate setting value
            $sanitizedValue = $this->sanitizeSettingValue($key, $value);
            
            $sql = "UPDATE settings SET setting_value = ? WHERE setting_key = ?";
            if ($this->db->query($sql, [$sanitizedValue, $key])) {
                $updated++;
            }
        }
        
        if ($updated > 0) {
            $this->logAdminAction('settings_updated', "Updated $updated settings");
            $this->setFlash('success', "$updated settings updated successfully");
        } else {
            $this->setFlash('error', 'No settings were updated');
        }
        
        $this->redirect('/admin/settings');
    }
    
    public function create() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();
            
            $key = $this->sanitize($_POST['setting_key']);
            $value = $this->sanitize($_POST['setting_value']);
            $description = $this->sanitize($_POST['description']);
            
            // Check if setting already exists
            $existing = $this->db->query("SELECT id FROM settings WHERE setting_key = ?", [$key])->fetch();
            if ($existing) {
                $this->setFlash('error', 'Setting with this key already exists');
                $this->redirect('/admin/settings');
            }
            
            $sql = "INSERT INTO settings (setting_key, setting_value, description) VALUES (?, ?, ?)";
            if ($this->db->query($sql, [$key, $value, $description])) {
                $this->logAdminAction('setting_created', "Created new setting: $key");
                $this->setFlash('success', 'Setting created successfully');
            } else {
                $this->setFlash('error', 'Failed to create setting');
            }
        }
        
        $this->render('admin/settings/create');
    }
    
    public function delete() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        $id = $this->getParam('id');
        
        // Get setting info before deletion
        $setting = $this->db->query("SELECT * FROM settings WHERE id = ?", [$id])->fetch();
        if (!$setting) {
            $this->jsonResponse(['success' => false, 'message' => 'Setting not found']);
            return;
        }
        
        // Prevent deletion of critical settings
        $criticalSettings = ['site_name', 'contact_email', 'gst_rate', 'currency'];
        if (in_array($setting['setting_key'], $criticalSettings)) {
            $this->jsonResponse(['success' => false, 'message' => 'Cannot delete critical setting']);
            return;
        }
        
        if ($this->db->query("DELETE FROM settings WHERE id = ?", [$id])) {
            $this->logAdminAction('setting_deleted', "Deleted setting: {$setting['setting_key']}");
            $this->jsonResponse(['success' => true, 'message' => 'Setting deleted successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to delete setting']);
        }
    }
    
    public function backup() {
        $this->requireAdmin();
        
        $settings = $this->db->query("SELECT * FROM settings ORDER BY setting_key")->fetchAll();
        
        $backup = [
            'created_at' => date('Y-m-d H:i:s'),
            'settings' => $settings
        ];
        
        header('Content-Type: application/json');
        header('Content-Disposition: attachment; filename="settings_backup_' . date('Y-m-d_H-i-s') . '.json"');
        
        echo json_encode($backup, JSON_PRETTY_PRINT);
        exit;
    }
    
    public function restore() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        if (!isset($_FILES['backup_file']) || $_FILES['backup_file']['error'] !== UPLOAD_ERR_OK) {
            $this->setFlash('error', 'Please select a valid backup file');
            $this->redirect('/admin/settings');
        }
        
        $backupContent = file_get_contents($_FILES['backup_file']['tmp_name']);
        $backup = json_decode($backupContent, true);
        
        if (!$backup || !isset($backup['settings'])) {
            $this->setFlash('error', 'Invalid backup file format');
            $this->redirect('/admin/settings');
        }
        
        $restored = 0;
        foreach ($backup['settings'] as $setting) {
            $sql = "INSERT INTO settings (setting_key, setting_value, description) 
                    VALUES (?, ?, ?) 
                    ON DUPLICATE KEY UPDATE 
                    setting_value = VALUES(setting_value), 
                    description = VALUES(description)";
            
            if ($this->db->query($sql, [
                $setting['setting_key'],
                $setting['setting_value'],
                $setting['description']
            ])) {
                $restored++;
            }
        }
        
        if ($restored > 0) {
            $this->logAdminAction('settings_restored', "Restored $restored settings from backup");
            $this->setFlash('success', "$restored settings restored successfully");
        } else {
            $this->setFlash('error', 'No settings were restored');
        }
        
        $this->redirect('/admin/settings');
    }
    
    public function clearCache() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        // Clear any cached settings or files
        $cacheCleared = 0;
        
        // Clear PHP opcache if available
        if (function_exists('opcache_reset')) {
            opcache_reset();
            $cacheCleared++;
        }
        
        // Clear any custom cache files
        $cacheDir = 'cache/';
        if (is_dir($cacheDir)) {
            $files = glob($cacheDir . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                    $cacheCleared++;
                }
            }
        }
        
        $this->logAdminAction('cache_cleared', "Cleared system cache");
        $this->jsonResponse([
            'success' => true, 
            'message' => "Cache cleared successfully ($cacheCleared items)"
        ]);
    }
    
    public function testEmail() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        $testEmail = $_POST['test_email'] ?? '';
        
        if (empty($testEmail) || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
            $this->jsonResponse(['success' => false, 'message' => 'Please provide a valid email address']);
            return;
        }
        
        // Get email settings
        $smtpHost = $this->getSetting('smtp_host', SMTP_HOST);
        $smtpPort = $this->getSetting('smtp_port', SMTP_PORT);
        $smtpUsername = $this->getSetting('smtp_username', SMTP_USERNAME);
        $smtpPassword = $this->getSetting('smtp_password', SMTP_PASSWORD);
        
        // Simple test email (in a real application, you'd use PHPMailer or similar)
        $subject = 'Test Email from ' . SITE_NAME;
        $message = "This is a test email sent from the admin panel of " . SITE_NAME . " at " . date('Y-m-d H:i:s');
        $headers = "From: " . $smtpUsername . "\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        
        if (mail($testEmail, $subject, $message, $headers)) {
            $this->logAdminAction('test_email_sent', "Sent test email to $testEmail");
            $this->jsonResponse(['success' => true, 'message' => 'Test email sent successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to send test email']);
        }
    }
    
    private function getSettingCategory($key) {
        $categories = [
            'site_name' => 'General',
            'site_logo' => 'General',
            'contact_email' => 'Contact',
            'contact_phone' => 'Contact',
            'smtp_host' => 'Email',
            'smtp_port' => 'Email',
            'smtp_username' => 'Email',
            'smtp_password' => 'Email',
            'payment_gateway' => 'Payment',
            'razorpay_key_id' => 'Payment',
            'razorpay_key_secret' => 'Payment',
            'gst_rate' => 'Financial',
            'currency' => 'Financial',
            'items_per_page' => 'Display',
            'maintenance_mode' => 'System'
        ];
        
        return $categories[$key] ?? 'Other';
    }
    
    private function sanitizeSettingValue($key, $value) {
        // Special handling for different setting types
        switch ($key) {
            case 'gst_rate':
            case 'items_per_page':
            case 'smtp_port':
                return (int)$value;
                
            case 'maintenance_mode':
                return $value ? 'true' : 'false';
                
            case 'contact_email':
            case 'smtp_username':
                return filter_var($value, FILTER_VALIDATE_EMAIL) ? $value : '';
                
            default:
                return $this->sanitize($value);
        }
    }
    
    private function getSetting($key, $default = '') {
        $setting = $this->db->query("SELECT setting_value FROM settings WHERE setting_key = ?", [$key])->fetch();
        return $setting ? json_decode($setting['setting_value'], true) : $default;
    }
}
?>
