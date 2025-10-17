<?php
class AdminOrderController extends Controller {
    protected $db;

    public function __construct() {
        parent::__construct();
        $this->db = Database::getInstance();
    }
    
    public function index() {
        $this->requireAdmin();
        
        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $payment_status = $_GET['payment_status'] ?? '';
        
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(u.name LIKE ? OR u.email LIKE ? OR r.title LIKE ? OR o.id LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($status) {
            $conditions[] = "o.booking_status = ?";
            $params[] = $status;
        }
        
        if ($payment_status) {
            $conditions[] = "o.payment_status = ?";
            $params[] = $payment_status;
        }
        
        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone,
                       r.title as room_title, r.address as room_address, r.city as room_city
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                JOIN rooms r ON o.room_id = r.id 
                $where
                ORDER BY o.created_at DESC 
                LIMIT " . ADMIN_ITEMS_PER_PAGE . " OFFSET " . (($page - 1) * ADMIN_ITEMS_PER_PAGE);
        
        $orders = $this->db->query($sql, $params)->fetchAll();
        
        $countSql = "SELECT COUNT(*) as total FROM orders o 
                     JOIN users u ON o.user_id = u.id 
                     JOIN rooms r ON o.room_id = r.id $where";
        $totalOrders = $this->db->query($countSql, $params)->fetch()['total'];
        $totalPages = ceil($totalOrders / ADMIN_ITEMS_PER_PAGE);
        
        // Get statistics
        $stats = [
            'total' => $this->db->query("SELECT COUNT(*) as count FROM orders")->fetch()['count'],
            'confirmed' => $this->db->query("SELECT COUNT(*) as count FROM orders WHERE booking_status = 'confirmed'")->fetch()['count'],
            'pending' => $this->db->query("SELECT COUNT(*) as count FROM orders WHERE booking_status = 'pending'")->fetch()['count'],
            'cancelled' => $this->db->query("SELECT COUNT(*) as count FROM orders WHERE booking_status = 'cancelled'")->fetch()['count'],
            'paid' => $this->db->query("SELECT COUNT(*) as count FROM orders WHERE payment_status = 'paid'")->fetch()['count'],
            'revenue' => $this->db->query("SELECT COALESCE(SUM(total_amount), 0) as total FROM orders WHERE payment_status = 'paid'")->fetch()['total']
        ];
        
        $this->render('admin/orders/index', [
            'orders' => $orders,
            'stats' => $stats,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'status' => $status,
            'payment_status' => $payment_status,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    public function create() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreate();
            return;
        }
        
        // Get all users and rooms for the form
        $users = $this->db->query("SELECT id, name, email, phone FROM users WHERE role = 'user' AND status = 'active' ORDER BY name")->fetchAll();
        $rooms = $this->db->query("SELECT id, title, price_per_month, deposit, availability_status, address, city FROM rooms WHERE status = 'active' ORDER BY title")->fetchAll();
        
        $this->render('admin/orders/create', [
            'page_title' => 'Create Order',
            'users' => $users,
            'rooms' => $rooms,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    private function handleCreate() {
        $this->validateCsrf();
        
        $data = $this->sanitize($_POST);
        
        // Validate required fields
        $required = ['user_id', 'room_id', 'start_date', 'months_count'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                $_SESSION['flash_error'] = ucfirst(str_replace('_', ' ', $field)) . ' is required';
                $this->redirect('/admin/orders/create');
                return;
            }
        }
        
        // Validate dates
        if (!strtotime($data['start_date'])) {
            $_SESSION['flash_error'] = 'Invalid start date';
            $this->redirect('/admin/orders/create');
            return;
        }
        
        // Get room details
        $room = $this->db->query("SELECT * FROM rooms WHERE id = ?", [$data['room_id']])->fetch();
        if (!$room) {
            $_SESSION['flash_error'] = 'Room not found';
            $this->redirect('/admin/orders/create');
            return;
        }
        
        // Get user details
        $user = $this->db->query("SELECT * FROM users WHERE id = ?", [$data['user_id']])->fetch();
        if (!$user) {
            $_SESSION['flash_error'] = 'User not found';
            $this->redirect('/admin/orders/create');
            return;
        }
        
        // Calculate end date and amounts
        $startDate = $data['start_date'];
        $months = (int)$data['months_count'];
        $endDate = date('Y-m-d', strtotime($startDate . " +{$months} months"));

        $monthlyRent = (float)$room['price_per_month'];
        $depositAmount = (float)$room['deposit'];
        $rentTotal = $monthlyRent * $months;
        $gstAmount = (float)($data['gst_amount'] ?? 0);
        $discountAmount = (float)($data['discount_amount'] ?? 0);
        $totalAmount = $rentTotal + $depositAmount + $gstAmount - $discountAmount;
        
        try {
            $sql = "INSERT INTO orders (user_id, room_id, start_date, end_date, months_count, 
                                       monthly_rent, deposit_amount, total_amount, gst_amount, 
                                       discount_amount, coupon_code, payment_status, booking_status, 
                                       payment_method, transaction_id, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $this->db->query($sql, [
                $data['user_id'],
                $data['room_id'],
                $startDate,
                $endDate,
                $months,
                $monthlyRent,
                $depositAmount,
                $totalAmount,
                $gstAmount,
                $discountAmount,
                $data['coupon_code'] ?? null,
                $data['payment_status'] ?? 'pending',
                $data['booking_status'] ?? 'confirmed',
                $data['payment_method'] ?? null,
                $data['transaction_id'] ?? null
            ]);
            
            $orderId = $this->db->lastInsertId();
            
            $this->logAdminAction('order_created', 'Created order #' . $orderId . ' for user: ' . $user['name'] . ' (' . $user['email'] . ')');
            
            $_SESSION['flash_success'] = 'Order created successfully! Order ID: #' . $orderId;
            $this->redirect('/admin/orders');
        } catch (Exception $e) {
            $_SESSION['flash_error'] = 'Failed to create order: ' . $e->getMessage();
            $this->redirect('/admin/orders/create');
        }
    }
    
    public function show($id = null) {
        $this->requireAdmin();
        
        $id = $id ?? $this->getParam('id');
        
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone,
                       r.title as room_title, r.description as room_description, r.address as room_address, 
                       r.city as room_city, r.images as room_images
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                JOIN rooms r ON o.room_id = r.id 
                WHERE o.id = ?";
        
        $order = $this->db->query($sql, [$id])->fetch();
        
        if (!$order) {
            $_SESSION['flash_error'] = 'Order not found';
            $this->redirect('/admin/orders');
            return;
        }
        
        $this->render('admin/orders/show', [
            'order' => $order,
            'page_title' => 'Order Details - #' . $order['id']
        ]);
    }
    
    public function updateStatus($id = null) {
        $this->requireAdmin();
        $this->validateCsrf();
        
        $id = $id ?? $this->getParam('id');
        $data = $this->sanitize($_POST);
        
        $order = $this->db->query("SELECT * FROM orders WHERE id = ?", [$id])->fetch();
        if (!$order) {
            $this->jsonResponse(['success' => false, 'message' => 'Order not found']);
            return;
        }
        
        $updates = [];
        $params = [];
        
        if (isset($data['booking_status'])) {
            $updates[] = "booking_status = ?";
            $params[] = $data['booking_status'];
        }
        
        if (isset($data['payment_status'])) {
            $updates[] = "payment_status = ?";
            $params[] = $data['payment_status'];
        }
        
        if (empty($updates)) {
            $this->jsonResponse(['success' => false, 'message' => 'No updates provided']);
            return;
        }
        
        $params[] = $id;
        $sql = "UPDATE orders SET " . implode(', ', $updates) . " WHERE id = ?";
        
        if ($this->db->query($sql, $params)) {
            $this->logAdminAction('order_updated', "Updated order #$id status");
            $this->jsonResponse(['success' => true, 'message' => 'Order status updated successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to update order status']);
        }
    }
    
    public function delete($id = null) {
        $this->requireAdmin();
        $this->validateCsrf();
        
        $id = $id ?? $this->getParam('id');
        $order = $this->db->query("SELECT * FROM orders WHERE id = ?", [$id])->fetch();
        
        if (!$order) {
            $this->jsonResponse(['success' => false, 'message' => 'Order not found']);
            return;
        }
        
        if ($this->db->query("DELETE FROM orders WHERE id = ?", [$id])) {
            $this->logAdminAction('order_deleted', "Deleted order #$id");
            $this->jsonResponse(['success' => true, 'message' => 'Order deleted successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to delete order']);
        }
    }
    
    public function export() {
        $this->requireAdmin();
        
        $format = $_GET['format'] ?? 'csv';
        
        $sql = "SELECT o.id, o.start_date, o.end_date, o.months_count, o.total_amount, 
                       o.payment_status, o.booking_status, o.created_at,
                       u.name as user_name, u.email as user_email, u.phone as user_phone,
                       r.title as room_title, r.city as room_city
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                JOIN rooms r ON o.room_id = r.id 
                ORDER BY o.created_at DESC";
        
        $orders = $this->db->query($sql)->fetchAll();
        
        if ($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="orders_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, ['Order ID', 'User Name', 'User Email', 'Room Title', 'City', 'Start Date', 'End Date', 'Months', 'Total Amount', 'Payment Status', 'Booking Status', 'Created At']);
            
            foreach ($orders as $order) {
                fputcsv($output, [
                    $order['id'],
                    $order['user_name'],
                    $order['user_email'],
                    $order['room_title'],
                    $order['room_city'],
                    $order['start_date'],
                    $order['end_date'],
                    $order['months_count'],
                    $order['total_amount'],
                    $order['payment_status'],
                    $order['booking_status'],
                    $order['created_at']
                ]);
            }
            
            fclose($output);
            exit;
        }
    }
}
?>
