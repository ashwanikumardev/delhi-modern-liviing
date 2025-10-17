<?php
class PaymentController extends Controller {
    
    public function process() {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        $this->validateCsrf();
        
        $paymentMethod = $_POST['payment_method'] ?? '';
        $orderId = $_POST['order_id'] ?? '';
        
        if (empty($orderId)) {
            $this->jsonResponse(['success' => false, 'message' => 'Order ID is required']);
            return;
        }
        
        $order = new Order();
        $orderData = $order->find($orderId);
        
        if (!$orderData || $orderData['user_id'] != $_SESSION['user_id']) {
            $this->jsonResponse(['success' => false, 'message' => 'Order not found']);
            return;
        }
        
        if ($orderData['payment_status'] === 'paid') {
            $this->jsonResponse(['success' => false, 'message' => 'Order already paid']);
            return;
        }
        
        switch ($paymentMethod) {
            case 'razorpay':
                $this->processRazorpayPayment($orderData);
                break;
                
            case 'bank_transfer':
                $this->processBankTransfer($orderData);
                break;
                
            case 'cash':
                $this->processCashPayment($orderData);
                break;
                
            default:
                $this->jsonResponse(['success' => false, 'message' => 'Invalid payment method']);
        }
    }
    
    private function processRazorpayPayment($orderData) {
        $razorpayPaymentId = $_POST['razorpay_payment_id'] ?? '';
        $razorpayOrderId = $_POST['razorpay_order_id'] ?? '';
        $razorpaySignature = $_POST['razorpay_signature'] ?? '';
        
        if (empty($razorpayPaymentId) || empty($razorpayOrderId) || empty($razorpaySignature)) {
            $this->jsonResponse(['success' => false, 'message' => 'Missing Razorpay payment details']);
            return;
        }
        
        // In a real application, you would verify the signature here
        // For demo purposes, we'll assume the payment is successful
        
        $order = new Order();
        $updateData = [
            'payment_status' => 'paid',
            'payment_method' => 'razorpay',
            'payment_id' => $razorpayPaymentId,
            'booking_status' => 'confirmed'
        ];
        
        if ($order->update($orderData['id'], $updateData)) {
            // Update room availability
            $room = new Room();
            $room->update($orderData['room_id'], ['availability_status' => 'occupied']);
            
            // Create download entry for invoice
            $this->createDownloadEntry($orderData['id'], 'invoice');
            
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Payment successful',
                'redirect' => '/orders'
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to update payment status']);
        }
    }
    
    private function processBankTransfer($orderData) {
        $order = new Order();
        $updateData = [
            'payment_status' => 'pending',
            'payment_method' => 'bank_transfer',
            'booking_status' => 'pending'
        ];
        
        if ($order->update($orderData['id'], $updateData)) {
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Bank transfer details recorded. Payment will be verified within 24 hours.',
                'redirect' => '/orders'
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to update payment status']);
        }
    }
    
    private function processCashPayment($orderData) {
        $order = new Order();
        $updateData = [
            'payment_status' => 'pending',
            'payment_method' => 'cash',
            'booking_status' => 'pending'
        ];
        
        if ($order->update($orderData['id'], $updateData)) {
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Cash payment option selected. Please pay at the property.',
                'redirect' => '/orders'
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to update payment status']);
        }
    }
    
    private function createDownloadEntry($orderId, $type) {
        $sql = "INSERT INTO downloads (order_id, file_type, file_path, download_token, expires_at) 
                VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY))";
        
        $token = bin2hex(random_bytes(32));
        $filePath = "invoices/invoice_{$orderId}.pdf";
        
        $this->db->query($sql, [$orderId, $type, $filePath, $token]);
    }
    
    public function webhook() {
        // Handle payment gateway webhooks
        $payload = file_get_contents('php://input');
        $headers = getallheaders();
        
        // Log webhook for debugging
        error_log("Payment webhook received: " . $payload);
        
        // In a real application, you would verify the webhook signature
        // and update payment status accordingly
        
        http_response_code(200);
        echo "OK";
    }
    
    public function refund() {
        $this->requireAuth();
        $this->validateCsrf();
        
        $orderId = $_POST['order_id'] ?? '';
        $reason = $_POST['reason'] ?? '';
        
        if (empty($orderId) || empty($reason)) {
            $this->jsonResponse(['success' => false, 'message' => 'Order ID and reason are required']);
            return;
        }
        
        $order = new Order();
        $orderData = $order->find($orderId);
        
        if (!$orderData || $orderData['user_id'] != $_SESSION['user_id']) {
            $this->jsonResponse(['success' => false, 'message' => 'Order not found']);
            return;
        }
        
        if ($orderData['payment_status'] !== 'paid') {
            $this->jsonResponse(['success' => false, 'message' => 'Order is not paid']);
            return;
        }
        
        // In a real application, you would process the refund through the payment gateway
        // For demo purposes, we'll just update the status
        
        $updateData = [
            'payment_status' => 'refunded',
            'booking_status' => 'cancelled'
        ];
        
        if ($order->update($orderId, $updateData)) {
            // Update room availability
            $room = new Room();
            $room->update($orderData['room_id'], ['availability_status' => 'available']);
            
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Refund request submitted successfully'
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to process refund request']);
        }
    }
}
?>
