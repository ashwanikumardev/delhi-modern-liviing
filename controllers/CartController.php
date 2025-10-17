<?php
class CartController extends Controller {
    private $cartModel;
    private $roomModel;
    
    public function __construct() {
        parent::__construct();
        $this->cartModel = new Cart();
        $this->roomModel = new Room();
    }
    
    public function index() {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        $cartItems = $this->cartModel->getUserCart($userId);
        $cartTotal = $this->cartModel->calculateCartTotal($userId);
        
        // Calculate GST
        $gstRate = 18;
        $baseAmount = $cartTotal;
        $gstAmount = ($baseAmount * $gstRate) / 100;
        $totalWithGst = $baseAmount + $gstAmount;
        
        $this->view('cart/index', [
            'cart_items' => $cartItems,
            'cart_total' => $cartTotal,
            'gst_amount' => $gstAmount,
            'total_with_gst' => $totalWithGst,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request method'], 405);
            return;
        }
        
        $this->requireAuth();
        
        $data = $this->sanitize($_POST);
        
        // Validate CSRF token
        if (!$this->validateCSRFToken($data['csrf_token'] ?? '')) {
            $this->json(['success' => false, 'message' => 'Invalid security token'], 403);
            return;
        }
        
        // Validate required fields
        $errors = $this->validate($data, [
            'room_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'months_count' => 'required'
        ]);
        
        if (!empty($errors)) {
            $this->json(['success' => false, 'message' => 'Invalid input data', 'errors' => $errors], 400);
            return;
        }
        
        // Validate room exists and is available
        $room = $this->roomModel->find($data['room_id']);
        if (!$room || $room['status'] !== 'active') {
            $this->json(['success' => false, 'message' => 'Room not found or not available'], 404);
            return;
        }
        
        // Validate dates
        $startDate = new DateTime($data['start_date']);
        $endDate = new DateTime($data['end_date']);
        $today = new DateTime();
        
        if ($startDate < $today) {
            $this->json(['success' => false, 'message' => 'Start date cannot be in the past'], 400);
            return;
        }
        
        if ($endDate <= $startDate) {
            $this->json(['success' => false, 'message' => 'End date must be after start date'], 400);
            return;
        }
        
        try {
            $userId = $_SESSION['user_id'];
            $result = $this->cartModel->addToCart(
                $userId,
                $data['room_id'],
                $data['start_date'],
                $data['end_date'],
                $data['months_count']
            );
            
            if ($result) {
                $cartCount = $this->cartModel->getCartCount($userId);
                $this->json([
                    'success' => true, 
                    'message' => 'Room added to cart successfully',
                    'cart_count' => $cartCount
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to add room to cart'], 500);
            }
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }
    
    public function remove() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['success' => false, 'message' => 'Invalid request method'], 405);
            return;
        }
        
        $this->requireAuth();
        
        $data = $this->sanitize($_POST);
        
        // Validate CSRF token
        if (!$this->validateCSRFToken($data['csrf_token'] ?? '')) {
            $this->json(['success' => false, 'message' => 'Invalid security token'], 403);
            return;
        }
        
        if (empty($data['room_id'])) {
            $this->json(['success' => false, 'message' => 'Room ID is required'], 400);
            return;
        }
        
        try {
            $userId = $_SESSION['user_id'];
            $result = $this->cartModel->removeFromCart($userId, $data['room_id']);
            
            if ($result) {
                $cartCount = $this->cartModel->getCartCount($userId);
                $this->json([
                    'success' => true, 
                    'message' => 'Room removed from cart successfully',
                    'cart_count' => $cartCount
                ]);
            } else {
                $this->json(['success' => false, 'message' => 'Failed to remove room from cart'], 500);
            }
        } catch (Exception $e) {
            $this->json(['success' => false, 'message' => 'An error occurred'], 500);
        }
    }
}
