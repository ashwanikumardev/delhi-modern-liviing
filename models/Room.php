<?php
class Room extends Model {
    protected $table = 'rooms';
    
    public function getFeaturedRooms($limit = 6) {
        return $this->findAll(['featured' => 1, 'status' => 'active'], 'created_at DESC', $limit);
    }
    
    public function searchRooms($filters = [], $page = 1, $perPage = 12) {
        $offset = ($page - 1) * $perPage;
        $conditions = ['status' => 'active'];
        $params = [];
        
        // Build WHERE conditions
        $whereClause = ['status = ?'];
        $params[] = 'active';
        
        if (!empty($filters['category'])) {
            $whereClause[] = 'category = ?';
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['city'])) {
            $whereClause[] = 'city LIKE ?';
            $params[] = '%' . $filters['city'] . '%';
        }
        
        if (!empty($filters['search'])) {
            $whereClause[] = '(title LIKE ? OR address LIKE ?)';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['min_price'])) {
            $whereClause[] = 'price_per_month >= ?';
            $params[] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $whereClause[] = 'price_per_month <= ?';
            $params[] = $filters['max_price'];
        }
        
        if (!empty($filters['availability'])) {
            $whereClause[] = 'availability_status = ?';
            $params[] = $filters['availability'];
        }
        
        // Order by
        $orderBy = 'created_at DESC';
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_low':
                    $orderBy = 'price_per_month ASC';
                    break;
                case 'price_high':
                    $orderBy = 'price_per_month DESC';
                    break;
                case 'popular':
                    $orderBy = 'views_count DESC';
                    break;
                case 'newest':
                    $orderBy = 'created_at DESC';
                    break;
            }
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $whereClause) . 
               " ORDER BY {$orderBy} LIMIT {$perPage} OFFSET {$offset}";
        
        $rooms = $this->db->fetchAll($sql, $params);
        
        // Get total count
        $countSql = "SELECT COUNT(*) as count FROM {$this->table} WHERE " . implode(' AND ', $whereClause);
        $total = $this->db->fetch($countSql, $params)['count'];
        
        return [
            'rooms' => $rooms,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
    
    public function incrementViews($roomId) {
        $sql = "UPDATE {$this->table} SET views_count = views_count + 1 WHERE id = ?";
        return $this->db->query($sql, [$roomId]);
    }
    
    public function getRelatedRooms($roomId, $category, $limit = 4) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id != ? AND category = ? AND status = 'active' 
                ORDER BY views_count DESC, created_at DESC 
                LIMIT ?";
        return $this->db->fetchAll($sql, [$roomId, $category, $limit]);
    }
    
    public function getAvailableRooms($startDate, $endDate) {
        $sql = "SELECT r.* FROM {$this->table} r
                WHERE r.status = 'active' 
                AND r.availability_status = 'available'
                AND r.id NOT IN (
                    SELECT DISTINCT room_id FROM orders 
                    WHERE booking_status = 'confirmed' 
                    AND ((start_date <= ? AND end_date >= ?) 
                         OR (start_date <= ? AND end_date >= ?)
                         OR (start_date >= ? AND end_date <= ?))
                )";
        
        return $this->db->fetchAll($sql, [
            $startDate, $startDate,
            $endDate, $endDate,
            $startDate, $endDate
        ]);
    }
    
    public function getStats() {
        $stats = [];
        
        $stats['total_rooms'] = $this->count();
        $stats['active_rooms'] = $this->count(['status' => 'active']);
        $stats['available_rooms'] = $this->count(['availability_status' => 'available']);
        $stats['occupied_rooms'] = $this->count(['availability_status' => 'occupied']);
        
        // Most viewed rooms
        $sql = "SELECT title, views_count FROM {$this->table} 
                WHERE status = 'active' 
                ORDER BY views_count DESC 
                LIMIT 5";
        $stats['most_viewed'] = $this->db->fetchAll($sql);
        
        // Category distribution
        $sql = "SELECT category, COUNT(*) as count FROM {$this->table} 
                WHERE status = 'active' 
                GROUP BY category";
        $stats['category_distribution'] = $this->db->fetchAll($sql);
        
        return $stats;
    }
    
    public function getRoomsForAdmin($page = 1, $perPage = 20, $filters = []) {
        $offset = ($page - 1) * $perPage;
        $whereClause = [];
        $params = [];
        
        if (!empty($filters['search'])) {
            $whereClause[] = '(title LIKE ? OR address LIKE ?)';
            $params[] = '%' . $filters['search'] . '%';
            $params[] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['category'])) {
            $whereClause[] = 'category = ?';
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['status'])) {
            $whereClause[] = 'status = ?';
            $params[] = $filters['status'];
        }
        
        $whereSQL = !empty($whereClause) ? 'WHERE ' . implode(' AND ', $whereClause) : '';
        
        $sql = "SELECT * FROM {$this->table} {$whereSQL} 
                ORDER BY created_at DESC 
                LIMIT {$perPage} OFFSET {$offset}";
        
        $rooms = $this->db->fetchAll($sql, $params);
        
        $countSql = "SELECT COUNT(*) as count FROM {$this->table} {$whereSQL}";
        $total = $this->db->fetch($countSql, $params)['count'];
        
        return [
            'rooms' => $rooms,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
}
