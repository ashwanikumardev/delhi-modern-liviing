<?php
class User extends Model {
    protected $table = 'users';
    
    public function createUser($data) {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['verification_token'] = bin2hex(random_bytes(32));
        unset($data['password']);
        
        return $this->create($data);
    }
    
    public function findByEmail($email) {
        $sql = "SELECT * FROM {$this->table} WHERE email = ?";
        return $this->db->fetch($sql, [$email]);
    }
    
    public function emailExists($email) {
        $user = $this->findByEmail($email);
        return $user !== false;
    }
    
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    public function updatePassword($userId, $newPassword) {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, ['password_hash' => $hash]);
    }
    
    public function generateResetToken($email) {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $sql = "UPDATE {$this->table} SET reset_token = ?, reset_token_expires = ? WHERE email = ?";
        $this->db->query($sql, [$token, $expires, $email]);
        
        return $token;
    }
    
    public function validateResetToken($token) {
        $sql = "SELECT * FROM {$this->table} WHERE reset_token = ? AND reset_token_expires > NOW()";
        return $this->db->fetch($sql, [$token]);
    }
    
    public function clearResetToken($userId) {
        return $this->update($userId, [
            'reset_token' => null,
            'reset_token_expires' => null
        ]);
    }
    
    public function verifyEmail($token) {
        $sql = "UPDATE {$this->table} SET email_verified = 1, verification_token = NULL WHERE verification_token = ?";
        return $this->db->query($sql, [$token])->rowCount() > 0;
    }
    
    public function getUsersWithBookingCount($page = 1, $perPage = 20, $search = '') {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = "WHERE role = 'user'";
        $params = [];
        
        if (!empty($search)) {
            $whereClause .= " AND (name LIKE ? OR email LIKE ? OR phone LIKE ?)";
            $searchTerm = "%$search%";
            $params = [$searchTerm, $searchTerm, $searchTerm];
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} $whereClause";
        $totalResult = $this->db->query($countSql, $params)->fetch();
        $total = $totalResult['total'];
        
        // Get users with booking count
        $sql = "SELECT u.*, 
                       COUNT(o.id) as booking_count,
                       SUM(CASE WHEN o.payment_status = 'paid' THEN o.total_amount ELSE 0 END) as total_spent
                FROM {$this->table} u
                LEFT JOIN orders o ON u.id = o.user_id
                $whereClause
                GROUP BY u.id
                ORDER BY u.created_at DESC
                LIMIT $perPage OFFSET $offset";
        
        $users = $this->db->query($sql, $params)->fetchAll();
        
        return [
            'users' => $users,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'total' => $total,
            'per_page' => $perPage
        ];
    }
    
    public function getStats() {
        $stats = [];
        
        $stats['total_users'] = $this->count(['role' => 'user']);
        $stats['active_users'] = $this->count(['role' => 'user', 'status' => 'active']);
        $stats['blocked_users'] = $this->count(['role' => 'user', 'status' => 'blocked']);
        
        // Daily active users (users who have orders in last 24 hours)
        $sql = "SELECT COUNT(DISTINCT u.id) as count FROM users u 
                JOIN orders o ON u.id = o.user_id 
                WHERE o.created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)";
        $result = $this->db->fetch($sql);
        $stats['daily_active_users'] = $result['count'];
        
        return $stats;
    }
}
