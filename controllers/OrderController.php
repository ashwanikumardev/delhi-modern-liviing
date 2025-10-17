<?php
class OrderController extends Controller {
    private $orderModel;
    private $downloadModel;
    
    public function __construct() {
        parent::__construct();
        $this->orderModel = new Order();
        $this->downloadModel = new Download();
    }
    
    public function index() {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 10;
        
        // Get user orders with pagination
        $result = $this->orderModel->getUserOrders($userId, $page, $perPage);
        
        $this->view('orders/index', [
            'orders' => $result['orders'],
            'pagination' => [
                'current_page' => $result['current_page'],
                'last_page' => $result['last_page'],
                'total' => $result['total'],
                'per_page' => $result['per_page']
            ]
        ]);
    }
    
    public function show($id) {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        
        // Get order details
        $order = $this->orderModel->getOrderWithDetails($id, $userId);
        
        if (!$order) {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }
        
        // Parse JSON fields
        $order['room_images'] = json_decode($order['room_images'], true) ?? [];
        $order['room_amenities'] = json_decode($order['room_amenities'], true) ?? [];
        
        // Get download links if available
        $downloads = $this->getOrderDownloads($id);
        
        $this->view('orders/show', [
            'order' => $order,
            'downloads' => $downloads
        ]);
    }
    
    public function download($orderId) {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            http_response_code(400);
            echo 'Download token is required';
            return;
        }
        
        // Verify download token and get file info
        $download = $this->downloadModel->validateDownloadToken($token, $orderId, $userId);
        
        if (!$download) {
            http_response_code(404);
            echo 'Download not found or expired';
            return;
        }
        
        // Check if download limit exceeded
        if ($download['download_count'] >= $download['max_downloads']) {
            http_response_code(403);
            echo 'Download limit exceeded';
            return;
        }
        
        $filePath = $download['file_path'];
        
        if (!file_exists($filePath)) {
            http_response_code(404);
            echo 'File not found';
            return;
        }
        
        // Increment download count
        $this->downloadModel->incrementDownloadCount($download['id']);
        
        // Set headers for file download
        $fileName = basename($filePath);
        $fileSize = filesize($filePath);
        $mimeType = $this->getMimeType($filePath);
        
        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . $fileSize);
        header('Cache-Control: private');
        header('Pragma: private');
        
        // Output file
        readfile($filePath);
        exit;
    }
    
    private function getOrderDownloads($orderId) {
        $sql = "SELECT * FROM downloads WHERE order_id = ? AND expires_at > NOW() ORDER BY created_at DESC";
        return $this->db->fetchAll($sql, [$orderId]);
    }
    
    public function invoice($id) {
        $this->requireAuth();

        $order = $this->orderModel->find($id);
        if (!$order) {
            http_response_code(404);
            include 'views/errors/404.php';
            return;
        }

        // Check if user owns this order or is admin
        if ($order['user_id'] != $_SESSION['user_id'] && !$this->isAdmin()) {
            http_response_code(403);
            include 'views/errors/403.php';
            return;
        }

        // Get related data
        $userModel = new User();
        $roomModel = new Room();

        $user = $userModel->find($order['user_id']);
        $room = $roomModel->find($order['room_id']);

        // Render invoice
        include 'views/orders/invoice.php';
    }

    private function getMimeType($filePath) {
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));

        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'zip' => 'application/zip'
        ];

        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }
}

class Download extends Model {
    protected $table = 'downloads';
    
    public function validateDownloadToken($token, $orderId, $userId) {
        $sql = "SELECT d.*, o.user_id 
                FROM {$this->table} d
                JOIN orders o ON d.order_id = o.id
                WHERE d.token = ? AND d.order_id = ? AND o.user_id = ? AND d.expires_at > NOW()";
        
        return $this->db->fetch($sql, [$token, $orderId, $userId]);
    }
    
    public function incrementDownloadCount($downloadId) {
        $sql = "UPDATE {$this->table} SET download_count = download_count + 1 WHERE id = ?";
        return $this->db->query($sql, [$downloadId]);
    }
    
    public function createDownload($orderId, $filePath, $fileType, $maxDownloads = 5, $expiryHours = 72) {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime("+{$expiryHours} hours"));
        
        return $this->create([
            'order_id' => $orderId,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'token' => $token,
            'expires_at' => $expiresAt,
            'max_downloads' => $maxDownloads
        ]);
    }
}
