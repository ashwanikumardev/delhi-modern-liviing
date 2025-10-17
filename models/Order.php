<?php
class Order extends Model {
    protected $table = 'orders';
    
    public function createOrder($data) {
        // Calculate GST
        $gstRate = 18; // 18% GST
        $baseAmount = $data['total_amount'];
        $gstAmount = ($baseAmount * $gstRate) / (100 + $gstRate);
        
        $data['gst_amount'] = round($gstAmount, 2);
        $data['created_at'] = date('Y-m-d H:i:s');
        
        return $this->create($data);
    }
    
    public function getUserOrders($userId, $page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT o.*, r.title as room_title, r.address as room_address, r.images as room_images
                FROM {$this->table} o
                JOIN rooms r ON o.room_id = r.id
                WHERE o.user_id = ?
                ORDER BY o.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        $orders = $this->db->fetchAll($sql, [$userId]);
        
        $countSql = "SELECT COUNT(*) as count FROM {$this->table} WHERE user_id = ?";
        $total = $this->db->fetch($countSql, [$userId])['count'];
        
        return [
            'orders' => $orders,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
    
    public function getOrderWithDetails($orderId, $userId = null) {
        $sql = "SELECT o.*, r.title as room_title, r.address as room_address, 
                       r.images as room_images, r.amenities as room_amenities,
                       u.name as user_name, u.email as user_email, u.phone as user_phone
                FROM {$this->table} o
                JOIN rooms r ON o.room_id = r.id
                JOIN users u ON o.user_id = u.id
                WHERE o.id = ?";
        
        $params = [$orderId];
        
        if ($userId) {
            $sql .= " AND o.user_id = ?";
            $params[] = $userId;
        }
        
        return $this->db->fetch($sql, $params);
    }
    
    public function updatePaymentStatus($orderId, $status, $transactionId = null, $gatewayResponse = null) {
        $data = [
            'payment_status' => $status,
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($transactionId) {
            $data['transaction_id'] = $transactionId;
        }
        
        if ($gatewayResponse) {
            $data['payment_gateway_response'] = json_encode($gatewayResponse);
        }
        
        return $this->update($orderId, $data);
    }
    
    public function getStats() {
        $stats = [];
        
        $stats['total_bookings'] = $this->count();
        $stats['confirmed_bookings'] = $this->count(['booking_status' => 'confirmed']);
        $stats['cancelled_bookings'] = $this->count(['booking_status' => 'cancelled']);
        $stats['pending_payments'] = $this->count(['payment_status' => 'pending']);
        
        // Revenue stats
        $sql = "SELECT 
                    SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as total_revenue,
                    SUM(CASE WHEN payment_status = 'paid' AND DATE(created_at) = CURDATE() THEN total_amount ELSE 0 END) as today_revenue,
                    SUM(CASE WHEN payment_status = 'paid' AND YEARWEEK(created_at) = YEARWEEK(NOW()) THEN total_amount ELSE 0 END) as week_revenue,
                    SUM(CASE WHEN payment_status = 'paid' AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW()) THEN total_amount ELSE 0 END) as month_revenue
                FROM {$this->table}";
        
        $revenueStats = $this->db->fetch($sql);
        $stats = array_merge($stats, $revenueStats);
        
        // Most booked rooms
        $sql = "SELECT r.title, COUNT(o.id) as booking_count
                FROM {$this->table} o
                JOIN rooms r ON o.room_id = r.id
                WHERE o.booking_status = 'confirmed'
                GROUP BY o.room_id, r.title
                ORDER BY booking_count DESC
                LIMIT 5";
        $stats['most_booked_rooms'] = $this->db->fetchAll($sql);
        
        // Monthly booking trend (last 6 months)
        $sql = "SELECT 
                    DATE_FORMAT(created_at, '%Y-%m') as month,
                    COUNT(*) as bookings,
                    SUM(CASE WHEN payment_status = 'paid' THEN total_amount ELSE 0 END) as revenue
                FROM {$this->table}
                WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month DESC";
        $stats['monthly_trend'] = $this->db->fetchAll($sql);
        
        return $stats;
    }
    
    public function getOrdersForAdmin($page = 1, $perPage = 20, $filters = []) {
        $offset = ($page - 1) * $perPage;
        $whereClause = [];
        $params = [];
        
        if (!empty($filters['search'])) {
            $whereClause[] = '(u.name LIKE ? OR u.email LIKE ? OR r.title LIKE ?)';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['payment_status'])) {
            $whereClause[] = 'o.payment_status = ?';
            $params[] = $filters['payment_status'];
        }
        
        if (!empty($filters['booking_status'])) {
            $whereClause[] = 'o.booking_status = ?';
            $params[] = $filters['booking_status'];
        }
        
        if (!empty($filters['date_from'])) {
            $whereClause[] = 'DATE(o.created_at) >= ?';
            $params[] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $whereClause[] = 'DATE(o.created_at) <= ?';
            $params[] = $filters['date_to'];
        }
        
        $whereSQL = !empty($whereClause) ? 'WHERE ' . implode(' AND ', $whereClause) : '';
        
        $sql = "SELECT o.*, r.title as room_title, u.name as user_name, u.email as user_email
                FROM {$this->table} o
                JOIN rooms r ON o.room_id = r.id
                JOIN users u ON o.user_id = u.id
                {$whereSQL}
                ORDER BY o.created_at DESC
                LIMIT {$perPage} OFFSET {$offset}";
        
        $orders = $this->db->fetchAll($sql, $params);
        
        $countSql = "SELECT COUNT(*) as count 
                     FROM {$this->table} o
                     JOIN rooms r ON o.room_id = r.id
                     JOIN users u ON o.user_id = u.id
                     {$whereSQL}";
        $total = $this->db->fetch($countSql, $params)['count'];
        
        return [
            'orders' => $orders,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
}
