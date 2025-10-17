<?php
class AdminController extends Controller {
    private $userModel;
    private $roomModel;
    private $orderModel;
    
    public function __construct() {
        parent::__construct();
        try {
            $this->userModel = new User();
            $this->roomModel = new Room();
            $this->orderModel = new Order();
        } catch (Exception $e) {
            // Handle database connection issues
            error_log("AdminController database error: " . $e->getMessage());
        }
    }
    
    public function dashboard() {
        $this->requireAdmin();
        
        try {
            // Get dashboard statistics with fallbacks
            $userStats = $this->getUserStats();
            $roomStats = $this->getRoomStats();
            $orderStats = $this->getOrderStats();
            
            // Recent activities
            $recentOrders = $this->getRecentOrders(5);
            $recentUsers = $this->getRecentUsers(5);
            
            // Monthly revenue chart data
            $monthlyRevenue = $this->getMonthlyRevenueData();
            
            // Top performing rooms
            $topRooms = $this->getTopRooms();
        } catch (Exception $e) {
            // Fallback data if database queries fail
            error_log("Dashboard error: " . $e->getMessage());
            $userStats = ['total_users' => 0, 'active_users' => 0, 'new_users_today' => 0];
            $roomStats = ['total_rooms' => 0, 'available_rooms' => 0, 'occupied_rooms' => 0];
            $orderStats = ['total_orders' => 0, 'pending_orders' => 0, 'total_revenue' => 0];
            $recentOrders = [];
            $recentUsers = [];
            $monthlyRevenue = [];
            $topRooms = [];
        }
        
        $this->view('admin/dashboard', [
            'user_stats' => $userStats,
            'room_stats' => $roomStats,
            'order_stats' => $orderStats,
            'recent_orders' => $recentOrders,
            'recent_users' => $recentUsers,
            'monthly_revenue' => $monthlyRevenue,
            'top_rooms' => $topRooms
        ]);
    }
    
    private function getUserStats() {
        $sql = "SELECT 
                    COUNT(*) as total_users,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_users,
                    SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as new_users_today
                FROM users WHERE role = 'user'";
        
        $result = $this->db->query($sql)->fetch();
        return $result ?: ['total_users' => 0, 'active_users' => 0, 'new_users_today' => 0];
    }
    
    private function getRoomStats() {
        $sql = "SELECT 
                    COUNT(*) as total_rooms,
                    SUM(CASE WHEN availability_status = 'available' THEN 1 ELSE 0 END) as available_rooms,
                    SUM(CASE WHEN availability_status = 'occupied' THEN 1 ELSE 0 END) as occupied_rooms
                FROM rooms WHERE status = 'active'";
        
        $result = $this->db->query($sql)->fetch();
        return $result ?: ['total_rooms' => 0, 'available_rooms' => 0, 'occupied_rooms' => 0];
    }
    
    private function getOrderStats() {
        $sql = "SELECT
                    COUNT(*) as total_bookings,
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
                    SUM(CASE WHEN booking_status = 'pending' THEN 1 ELSE 0 END) as pending_bookings,
                    SUM(CASE WHEN booking_status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                    SUM(CASE WHEN booking_status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings,
                    SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as total_revenue,
                    SUM(CASE WHEN payment_status = 'pending' THEN total_amount ELSE 0 END) as pending_revenue
                FROM orders";

        $result = $this->db->query($sql)->fetch();
        return $result ?: [
            'total_bookings' => 0,
            'total_orders' => 0,
            'confirmed_bookings' => 0,
            'pending_bookings' => 0,
            'pending_orders' => 0,
            'cancelled_bookings' => 0,
            'total_revenue' => 0,
            'pending_revenue' => 0
        ];
    }
    
    private function getRecentOrders($limit = 5) {
        $sql = "SELECT o.*, r.title as room_title, u.name as user_name, u.email as user_email
                FROM orders o
                JOIN rooms r ON o.room_id = r.id
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    private function getRecentUsers($limit = 5) {
        $sql = "SELECT id, name, email, created_at, status
                FROM users 
                WHERE role = 'user'
                ORDER BY created_at DESC
                LIMIT ?";
        
        return $this->db->fetchAll($sql, [$limit]);
    }
    
    private function getMonthlyRevenueData() {
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    DATE_FORMAT(created_at, '%M %Y') as month_name,
                    COUNT(*) as bookings,
                    SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as revenue
                FROM orders
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month ASC";
        
        return $this->db->fetchAll($sql);
    }
    
    private function getTopRooms($limit = 5) {
        $sql = "SELECT r.id, r.title, r.price_per_month, r.category,
                       COUNT(o.id) as booking_count,
                       SUM(CASE WHEN o.payment_status = 'paid' THEN o.total_amount ELSE 0 END) as total_revenue
                FROM rooms r
                LEFT JOIN orders o ON r.id = o.room_id
                WHERE r.status = 'active'
                GROUP BY r.id
                ORDER BY booking_count DESC, total_revenue DESC
                LIMIT ?";
        
        return $this->db->query($sql, [$limit])->fetchAll();
    }
}
