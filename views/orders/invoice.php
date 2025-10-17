<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $order['id'] ?> - Delhi Modern Living</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f8f9fa;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 10px;
            overflow: hidden;
        }
        
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .invoice-header h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 300;
        }
        
        .invoice-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .invoice-body {
            padding: 30px;
        }
        
        .invoice-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .info-section h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.2rem;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 5px;
        }
        
        .info-section p {
            margin-bottom: 8px;
            font-size: 0.95rem;
        }
        
        .info-section strong {
            color: #333;
            font-weight: 600;
        }
        
        .booking-details {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            border-left: 4px solid #667eea;
        }
        
        .booking-details h3 {
            color: #667eea;
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .room-info {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            align-items: center;
        }
        
        .room-image {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e9ecef;
        }
        
        .room-placeholder {
            width: 100%;
            height: 120px;
            background: #e9ecef;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 2rem;
        }
        
        .room-details h4 {
            color: #333;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }
        
        .room-meta {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .room-meta span {
            background: #667eea;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .billing-table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .billing-table th {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        .billing-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .billing-table tr:last-child td {
            border-bottom: none;
        }
        
        .billing-table .amount {
            text-align: right;
            font-weight: 600;
        }
        
        .total-row {
            background: #f8f9fa;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        .total-row td {
            border-top: 2px solid #667eea;
            padding: 15px;
        }
        
        .payment-status {
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .payment-paid {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .payment-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        
        .payment-failed {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .invoice-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #667eea;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            transition: all 0.3s ease;
        }
        
        .print-button:hover {
            background: #5a6fd8;
            transform: translateY(-2px);
        }
        
        @media print {
            body {
                background: white;
            }
            
            .invoice-container {
                box-shadow: none;
                margin: 0;
                border-radius: 0;
            }
            
            .print-button {
                display: none;
            }
        }
        
        @media (max-width: 768px) {
            .invoice-info {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .room-info {
                grid-template-columns: 1fr;
            }
            
            .room-meta {
                flex-wrap: wrap;
            }
            
            .billing-table {
                font-size: 0.9rem;
            }
            
            .billing-table th,
            .billing-table td {
                padding: 10px 8px;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print Invoice
    </button>
    
    <div class="invoice-container">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <p>Delhi Modern Living - Premium PG Accommodation</p>
        </div>
        
        <!-- Invoice Body -->
        <div class="invoice-body">
            <!-- Invoice Information -->
            <div class="invoice-info">
                <div class="info-section">
                    <h3>Invoice Details</h3>
                    <p><strong>Invoice Number:</strong> INV-<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?></p>
                    <p><strong>Order ID:</strong> #<?= $order['id'] ?></p>
                    <p><strong>Invoice Date:</strong> <?= date('M d, Y', strtotime($order['created_at'])) ?></p>
                    <p><strong>Due Date:</strong> <?= date('M d, Y', strtotime($order['check_in_date'])) ?></p>
                    <p><strong>Status:</strong> 
                        <span style="color: <?= $order['status'] === 'confirmed' ? '#28a745' : ($order['status'] === 'pending' ? '#ffc107' : '#dc3545') ?>">
                            <?= ucfirst($order['status']) ?>
                        </span>
                    </p>
                </div>
                
                <div class="info-section">
                    <h3>Customer Information</h3>
                    <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($user['phone'] ?? 'Not provided') ?></p>
                    <p><strong>Customer ID:</strong> #<?= str_pad($user['id'], 4, '0', STR_PAD_LEFT) ?></p>
                </div>
            </div>
            
            <!-- Booking Details -->
            <div class="booking-details">
                <h3>Booking Information</h3>
                <div class="room-info">
                    <div>
                        <?php
                        require_once 'helpers/ImageHelper.php';
                        $roomImages = json_decode($order['room_images'] ?? '[]', true);
                        ?>
                        <?php if (!empty($roomImages)): ?>
                            <img src="<?= ImageHelper::getImageUrl($roomImages[0]) ?>" alt="Room" class="room-image">
                        <?php else: ?>
                            <img src="<?= url('assets/images/placeholder-room.jpg') ?>" alt="Room" class="room-image">
                        <?php endif; ?>
                    </div>
                    <div class="room-details">
                        <h4><?= htmlspecialchars($room['title']) ?></h4>
                        <p><strong>Location:</strong> <?= htmlspecialchars($room['location']) ?></p>
                        <p><strong>Check-in:</strong> <?= date('M d, Y', strtotime($order['start_date'])) ?></p>
                        <p><strong>Check-out:</strong> <?= date('M d, Y', strtotime($order['end_date'])) ?></p>
                        <div class="room-meta">
                            <span><?= ucfirst($room['category']) ?></span>
                            <span><?= $room['capacity'] ?> Person(s)</span>
                            <span><?= $order['guests'] ?> Guest(s)</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Billing Table -->
            <table class="billing-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Duration</th>
                        <th>Rate</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Room Rent - <?= htmlspecialchars($room['title']) ?></td>
                        <td>
                            <?php 
                            $duration = ceil((strtotime($order['end_date']) - strtotime($order['start_date'])) / (30 * 24 * 60 * 60));
                            echo $duration . ' month(s)';
                            ?>
                        </td>
                        <td class="amount">₹<?= number_format($room['price']) ?>/month</td>
                        <td class="amount">₹<?= number_format($order['room_amount']) ?></td>
                    </tr>
                    
                    <?php if ($order['security_deposit'] > 0): ?>
                    <tr>
                        <td>Security Deposit</td>
                        <td>One-time</td>
                        <td class="amount">-</td>
                        <td class="amount">₹<?= number_format($order['security_deposit']) ?></td>
                    </tr>
                    <?php endif; ?>
                    
                    <?php if ($order['service_fee'] > 0): ?>
                    <tr>
                        <td>Service Fee</td>
                        <td>One-time</td>
                        <td class="amount">-</td>
                        <td class="amount">₹<?= number_format($order['service_fee']) ?></td>
                    </tr>
                    <?php endif; ?>
                    
                    <?php if ($order['taxes'] > 0): ?>
                    <tr>
                        <td>Taxes & Government Fees</td>
                        <td>-</td>
                        <td class="amount">-</td>
                        <td class="amount">₹<?= number_format($order['taxes']) ?></td>
                    </tr>
                    <?php endif; ?>
                    
                    <tr class="total-row">
                        <td colspan="3"><strong>TOTAL AMOUNT</strong></td>
                        <td class="amount"><strong>₹<?= number_format($order['total_amount']) ?></strong></td>
                    </tr>
                </tbody>
            </table>
            
            <!-- Payment Status -->
            <div class="payment-status payment-<?= $order['payment_status'] ?>">
                <?php if ($order['payment_status'] === 'paid'): ?>
                    ✅ Payment Completed
                    <?php if (!empty($order['payment_method'])): ?>
                        - Paid via <?= ucfirst($order['payment_method']) ?>
                    <?php endif; ?>
                <?php elseif ($order['payment_status'] === 'pending'): ?>
                    ⏳ Payment Pending - Please complete your payment
                <?php else: ?>
                    ❌ Payment Failed - Please contact support
                <?php endif; ?>
            </div>
            
            <?php if (!empty($order['special_requests'])): ?>
            <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 8px;">
                <h3 style="color: #667eea; margin-bottom: 10px;">Special Requests</h3>
                <p><?= nl2br(htmlspecialchars($order['special_requests'])) ?></p>
            </div>
            <?php endif; ?>
        </div>
        
        <!-- Invoice Footer -->
        <div class="invoice-footer">
            <p><strong>Delhi Modern Living</strong></p>
            <p>Premium PG Accommodation Services | Email: info@delhimodernliving.com | Phone: +91-XXXXXXXXXX</p>
            <p>Thank you for choosing Delhi Modern Living for your accommodation needs!</p>
            <p style="margin-top: 10px; font-size: 0.8rem;">
                This is a computer-generated invoice. For any queries, please contact our support team.
            </p>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>
