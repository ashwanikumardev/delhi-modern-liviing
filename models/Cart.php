<?php
class Cart extends Model {
    protected $table = 'cart';
    
    public function addToCart($userId, $roomId, $startDate, $endDate, $monthsCount) {
        // Check if item already exists in cart
        $existing = $this->findAll(['user_id' => $userId, 'room_id' => $roomId]);
        
        if ($existing) {
            // Update existing cart item
            return $this->update($existing[0]['id'], [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'months_count' => $monthsCount,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Add new cart item
            return $this->create([
                'user_id' => $userId,
                'room_id' => $roomId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'months_count' => $monthsCount
            ]);
        }
    }
    
    public function getUserCart($userId) {
        $sql = "SELECT c.*, r.title, r.price_per_month, r.deposit, r.images, r.address
                FROM {$this->table} c
                JOIN rooms r ON c.room_id = r.id
                WHERE c.user_id = ? AND r.status = 'active'
                ORDER BY c.created_at DESC";
        
        return $this->db->fetchAll($sql, [$userId]);
    }
    
    public function removeFromCart($userId, $roomId) {
        $sql = "DELETE FROM {$this->table} WHERE user_id = ? AND room_id = ?";
        return $this->db->query($sql, [$userId, $roomId]);
    }
    
    public function clearUserCart($userId) {
        return $this->delete(['user_id' => $userId]);
    }
    
    public function getCartCount($userId) {
        return $this->count(['user_id' => $userId]);
    }
    
    public function calculateCartTotal($userId) {
        $cartItems = $this->getUserCart($userId);
        $total = 0;
        
        foreach ($cartItems as $item) {
            $total += $item['price_per_month'] * $item['months_count'];
        }
        
        return $total;
    }
}
