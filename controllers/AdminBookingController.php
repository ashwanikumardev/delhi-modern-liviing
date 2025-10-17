<?php
class AdminBookingController extends Controller {
    
    public function index() {
        $this->requireAdmin();
        
        $order = new Order();
        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $payment_status = $_GET['payment_status'] ?? '';
        
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(u.name LIKE ? OR u.email LIKE ? OR r.title LIKE ?)";
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
        
        $bookings = $this->db->query($sql, $params)->fetchAll();
        
        $countSql = "SELECT COUNT(*) as total FROM orders o 
                     JOIN users u ON o.user_id = u.id 
                     JOIN rooms r ON o.room_id = r.id $where";
        $totalBookings = $this->db->query($countSql, $params)->fetch()['total'];
        $totalPages = ceil($totalBookings / ADMIN_ITEMS_PER_PAGE);
        
        // Get statistics
        $stats = [
            'total' => $order->count(),
            'pending' => $order->count("WHERE booking_status = 'pending'"),
            'confirmed' => $order->count("WHERE booking_status = 'confirmed'"),
            'active' => $order->count("WHERE booking_status = 'active'"),
            'completed' => $order->count("WHERE booking_status = 'completed'"),
            'cancelled' => $order->count("WHERE booking_status = 'cancelled'"),
            'paid' => $order->count("WHERE payment_status = 'paid'"),
            'pending_payment' => $order->count("WHERE payment_status = 'pending'")
        ];
        
        $this->render('admin/bookings/index', [
            'bookings' => $bookings,
            'stats' => $stats,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'status' => $status,
            'payment_status' => $payment_status
        ]);
    }
    
    public function viewBooking($id) {
        $this->requireAdmin();
        
        $sql = "SELECT o.*, u.name as user_name, u.email as user_email, u.phone as user_phone,
                       r.title as room_title, r.description as room_description, 
                       r.address as room_address, r.city as room_city, r.pincode as room_pincode,
                       r.price_per_month, r.deposit
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                JOIN rooms r ON o.room_id = r.id 
                WHERE o.id = ?";
        
        $booking = $this->db->query($sql, [$id])->fetch();
        
        if (!$booking) {
            $_SESSION['flash_error'] = 'Booking not found';
            $this->redirect('/admin/bookings');
            return;
        }
        
        $this->view('admin/bookings/show', ['booking' => $booking]);
    }
    
    public function approve($id) {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request method'], 405);
            return;
        }
        
        $this->validateCsrf();
        
        try {
            $orderModel = new Order();
            $booking = $orderModel->find($id);
            
            if (!$booking) {
                $this->json(['success' => false, 'message' => 'Booking not found'], 404);
                return;
            }
            
            $orderModel->update($id, [
                'booking_status' => 'confirmed',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            $_SESSION['flash_success'] = 'Booking approved successfully';
            $this->json(['success' => true, 'message' => 'Booking approved successfully']);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Failed to approve booking'], 500);
        }
    }
    
    public function reject($id) {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request method'], 405);
            return;
        }
        
        $this->validateCsrf();
        
        try {
            $orderModel = new Order();
            $booking = $orderModel->find($id);
            
            if (!$booking) {
                $this->json(['success' => false, 'message' => 'Booking not found'], 404);
                return;
            }
            
            $orderModel->update($id, [
                'booking_status' => 'cancelled',
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            
            $_SESSION['flash_success'] = 'Booking rejected successfully';
            $this->json(['success' => true, 'message' => 'Booking rejected successfully']);
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'Failed to reject booking'], 500);
        }
    }
    
    public function updateStatus() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        $id = $this->getParam('id');
        $status = $_POST['status'] ?? '';
        
        $validStatuses = ['pending', 'confirmed', 'active', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid status']);
            return;
        }
        
        $order = new Order();
        $booking = $order->find($id);
        
        if (!$booking) {
            $this->jsonResponse(['success' => false, 'message' => 'Booking not found']);
            return;
        }
        
        if ($order->update($id, ['booking_status' => $status])) {
            $this->logAdminAction('booking_status_updated', "Updated booking #$id status to $status");
            $this->jsonResponse(['success' => true, 'message' => 'Booking status updated successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to update booking status']);
        }
    }
    
    public function updatePaymentStatus() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        $id = $this->getParam('id');
        $paymentStatus = $_POST['payment_status'] ?? '';
        
        $validStatuses = ['pending', 'paid', 'failed', 'refunded'];
        if (!in_array($paymentStatus, $validStatuses)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid payment status']);
            return;
        }
        
        $order = new Order();
        $booking = $order->find($id);
        
        if (!$booking) {
            $this->jsonResponse(['success' => false, 'message' => 'Booking not found']);
            return;
        }
        
        if ($order->update($id, ['payment_status' => $paymentStatus])) {
            $this->logAdminAction('payment_status_updated', "Updated booking #$id payment status to $paymentStatus");
            $this->jsonResponse(['success' => true, 'message' => 'Payment status updated successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to update payment status']);
        }
    }
    
    public function delete() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        $id = $this->getParam('id');
        $order = new Order();
        $booking = $order->find($id);
        
        if (!$booking) {
            $this->jsonResponse(['success' => false, 'message' => 'Booking not found']);
            return;
        }
        
        if ($order->delete($id)) {
            $this->logAdminAction('booking_deleted', "Deleted booking #$id");
            $this->jsonResponse(['success' => true, 'message' => 'Booking deleted successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to delete booking']);
        }
    }
    
    public function export() {
        $this->requireAdmin();
        
        $format = $_GET['format'] ?? 'csv';
        $status = $_GET['status'] ?? '';
        $payment_status = $_GET['payment_status'] ?? '';
        
        $conditions = [];
        $params = [];
        
        if ($status) {
            $conditions[] = "o.booking_status = ?";
            $params[] = $status;
        }
        
        if ($payment_status) {
            $conditions[] = "o.payment_status = ?";
            $params[] = $payment_status;
        }
        
        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT o.id, o.start_date, o.end_date, o.months_count, o.total_amount,
                       o.payment_status, o.booking_status, o.created_at,
                       u.name as user_name, u.email as user_email, u.phone as user_phone,
                       r.title as room_title, r.address as room_address, r.city as room_city
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                JOIN rooms r ON o.room_id = r.id 
                $where
                ORDER BY o.created_at DESC";
        
        $bookings = $this->db->query($sql, $params)->fetchAll();
        
        if ($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="bookings_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($output, [
                'Booking ID', 'User Name', 'User Email', 'User Phone',
                'Room Title', 'Room Address', 'Room City',
                'Start Date', 'End Date', 'Months', 'Total Amount',
                'Payment Status', 'Booking Status', 'Created At'
            ]);
            
            // CSV data
            foreach ($bookings as $booking) {
                fputcsv($output, [
                    $booking['id'],
                    $booking['user_name'],
                    $booking['user_email'],
                    $booking['user_phone'],
                    $booking['room_title'],
                    $booking['room_address'],
                    $booking['room_city'],
                    $booking['start_date'],
                    $booking['end_date'],
                    $booking['months_count'],
                    $booking['total_amount'],
                    $booking['payment_status'],
                    $booking['booking_status'],
                    $booking['created_at']
                ]);
            }
            
            fclose($output);
            exit;
        }
    }
    
    public function create() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleCreate();
            return;
        }
        
        // Get all users and rooms for the form
        $userModel = new User();
        $roomModel = new Room();
        
        $users = $this->db->query("SELECT id, name, email FROM users WHERE role = 'user' AND status = 'active' ORDER BY name")->fetchAll();
        $rooms = $this->db->query("SELECT id, title, price_per_month, deposit, availability_status FROM rooms WHERE status = 'active' ORDER BY title")->fetchAll();
        
        $this->view('admin/bookings/create', [
            'page_title' => 'Create Booking',
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
                $this->redirect('/admin/bookings/create');
                return;
            }
        }
        
        // Get room details
        $room = $this->db->query("SELECT * FROM rooms WHERE id = ?", [$data['room_id']])->fetch();
        if (!$room) {
            $_SESSION['flash_error'] = 'Room not found';
            $this->redirect('/admin/bookings/create');
            return;
        }
        
        // Calculate end date and total amount
        $startDate = $data['start_date'];
        $months = (int)$data['months_count'];
        $endDate = date('Y-m-d', strtotime($startDate . " +{$months} months"));
        $totalAmount = ($room['price_per_month'] * $months) + $room['deposit'];
        
        try {
            $sql = "INSERT INTO orders (user_id, room_id, start_date, end_date, months_count, total_amount, 
                                       payment_status, booking_status, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            
            $this->db->query($sql, [
                $data['user_id'],
                $data['room_id'],
                $startDate,
                $endDate,
                $months,
                $totalAmount,
                $data['payment_status'] ?? 'pending',
                $data['booking_status'] ?? 'confirmed'
            ]);
            
            $bookingId = $this->db->lastInsertId();
            
            $this->logAdminAction('booking_created', 'Created booking #' . $bookingId . ' for user ID: ' . $data['user_id']);
            
            $_SESSION['flash_success'] = 'Booking created successfully';
            $this->redirect('/admin/bookings');
        } catch (Exception $e) {
            $_SESSION['flash_error'] = 'Failed to create booking: ' . $e->getMessage();
            $this->redirect('/admin/bookings/create');
        }
    }
}
?>
