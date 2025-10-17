<?php
class CheckoutController extends Controller {
    private $cartModel;
    private $orderModel;
    private $roomModel;
    private $userModel;
    
    public function __construct() {
        parent::__construct();
        $this->cartModel = new Cart();
        $this->orderModel = new Order();
        $this->roomModel = new Room();
        $this->userModel = new User();
    }
    
    public function index() {
        $this->requireAuth();
        
        $userId = $_SESSION['user_id'];
        
        // Get cart items
        $cartItems = $this->cartModel->getUserCart($userId);
        
        if (empty($cartItems)) {
            $_SESSION['error'] = 'Your cart is empty. Please add rooms before checkout.';
            $this->redirect('/cart');
            return;
        }
        
        // Calculate totals
        $subtotal = $this->cartModel->calculateCartTotal($userId);
        $gstRate = 18;
        $gstAmount = ($subtotal * $gstRate) / 100;
        $totalAmount = $subtotal + $gstAmount;
        
        // Get user details
        $user = $this->userModel->find($userId);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processCheckout($cartItems, $subtotal, $gstAmount, $totalAmount);
            return;
        }
        
        $this->view('checkout/index', [
            'cart_items' => $cartItems,
            'subtotal' => $subtotal,
            'gst_amount' => $gstAmount,
            'total_amount' => $totalAmount,
            'user' => $user,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    private function processCheckout($cartItems, $subtotal, $gstAmount, $totalAmount) {
        $data = $this->sanitize($_POST);
        
        // Validate CSRF token
        if (!$this->validateCSRFToken($data['csrf_token'] ?? '')) {
            $_SESSION['error'] = 'Invalid security token';
            $this->redirect('/checkout');
            return;
        }
        
        // Validate billing information
        $errors = $this->validate($data, [
            'billing_name' => 'required|min:2',
            'billing_email' => 'required|email',
            'billing_phone' => 'required',
            'billing_address' => 'required',
            'payment_method' => 'required'
        ]);
        
        if (!empty($errors)) {
            $_SESSION['error'] = 'Please fill all required fields correctly.';
            $this->redirect('/checkout');
            return;
        }
        
        try {
            // Begin transaction
            $this->db->getConnection()->beginTransaction();
            
            $userId = $_SESSION['user_id'];
            $orderIds = [];
            
            // Create orders for each cart item
            foreach ($cartItems as $item) {
                // Verify room is still available
                $room = $this->roomModel->find($item['room_id']);
                if (!$room || $room['status'] !== 'active' || $room['availability_status'] !== 'available') {
                    throw new Exception("Room '{$item['title']}' is no longer available");
                }
                
                // Calculate item totals
                $monthlyRent = $item['price_per_month'];
                $monthsCount = $item['months_count'];
                $rentTotal = $monthlyRent * $monthsCount;
                $depositAmount = $item['deposit'];
                $itemSubtotal = $rentTotal + $depositAmount;
                $itemGst = ($itemSubtotal * 18) / 100;
                $itemTotal = $itemSubtotal + $itemGst;
                
                // Create order
                $orderId = $this->orderModel->create([
                    'user_id' => $userId,
                    'room_id' => $item['room_id'],
                    'start_date' => $item['start_date'],
                    'end_date' => $item['end_date'],
                    'months_count' => $monthsCount,
                    'monthly_rent' => $monthlyRent,
                    'deposit_amount' => $depositAmount,
                    'total_amount' => $itemTotal,
                    'gst_amount' => $itemGst,
                    'payment_status' => 'pending',
                    'payment_method' => $data['payment_method'],
                    'booking_status' => 'confirmed'
                ]);
                
                $orderIds[] = $orderId;
                
                // Update room availability
                $this->roomModel->update($item['room_id'], [
                    'availability_status' => 'occupied'
                ]);
            }
            
            // Clear cart
            $this->cartModel->clearUserCart($userId);
            
            // Commit transaction
            $this->db->getConnection()->commit();
            
            // Process payment based on method
            $paymentResult = $this->processPayment($data['payment_method'], $orderIds, $totalAmount, $data);
            
            if ($paymentResult['success']) {
                $_SESSION['success'] = 'Orders placed successfully! Payment confirmation will be sent to your email.';
                $this->redirect('/orders');
            } else {
                $_SESSION['error'] = $paymentResult['message'];
                $this->redirect('/checkout');
            }
            
        } catch (Exception $e) {
            // Rollback transaction
            $this->db->getConnection()->rollBack();
            
            $_SESSION['error'] = $e->getMessage();
            $this->redirect('/checkout');
        }
    }
    
    private function processPayment($paymentMethod, $orderIds, $amount, $billingData) {
        switch ($paymentMethod) {
            case 'razorpay':
                return $this->processRazorpayPayment($orderIds, $amount, $billingData);
            
            case 'bank_transfer':
                return $this->processBankTransferPayment($orderIds, $amount, $billingData);
            
            case 'cash':
                return $this->processCashPayment($orderIds, $amount, $billingData);
            
            default:
                return ['success' => false, 'message' => 'Invalid payment method'];
        }
    }
    
    private function processRazorpayPayment($orderIds, $amount, $billingData) {
        // In a real implementation, you would integrate with Razorpay API
        // For now, we'll simulate a successful payment
        
        try {
            // Update order payment status
            foreach ($orderIds as $orderId) {
                $this->orderModel->updatePaymentStatus($orderId, 'paid', 'razorpay_' . uniqid(), [
                    'payment_method' => 'razorpay',
                    'amount' => $amount,
                    'status' => 'success'
                ]);
            }
            
            return ['success' => true, 'message' => 'Payment processed successfully'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Payment processing failed: ' . $e->getMessage()];
        }
    }
    
    private function processBankTransferPayment($orderIds, $amount, $billingData) {
        // For bank transfer, keep payment status as pending
        // Admin will manually update when payment is received
        
        return ['success' => true, 'message' => 'Order placed successfully. Please complete the bank transfer and share the receipt.'];
    }
    
    private function processCashPayment($orderIds, $amount, $billingData) {
        // For cash payment, keep payment status as pending
        // Payment will be collected on arrival
        
        return ['success' => true, 'message' => 'Order placed successfully. Payment will be collected upon arrival.'];
    }
}
