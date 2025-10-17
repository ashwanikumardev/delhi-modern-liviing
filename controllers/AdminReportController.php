<?php
class AdminReportController extends Controller {
    
    public function index() {
        $this->requireAdmin();
        
        $dateRange = $_GET['date_range'] ?? '30';
        $reportType = $_GET['report_type'] ?? 'overview';
        
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime("-{$dateRange} days"));
        
        $data = [];
        
        switch ($reportType) {
            case 'overview':
                $data = $this->getOverviewReport($startDate, $endDate);
                break;
            case 'revenue':
                $data = $this->getRevenueReport($startDate, $endDate);
                break;
            case 'bookings':
                $data = $this->getBookingsReport($startDate, $endDate);
                break;
            case 'users':
                $data = $this->getUsersReport($startDate, $endDate);
                break;
            case 'rooms':
                $data = $this->getRoomsReport($startDate, $endDate);
                break;
        }
        
        $this->render('admin/reports/index', [
            'data' => $data,
            'dateRange' => $dateRange,
            'reportType' => $reportType,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
    
    private function getOverviewReport($startDate, $endDate) {
        // Total revenue
        $revenueQuery = "SELECT SUM(total_amount) as total_revenue 
                        FROM orders 
                        WHERE payment_status = 'paid' 
                        AND created_at BETWEEN ? AND ?";
        $totalRevenue = $this->db->query($revenueQuery, [$startDate, $endDate . ' 23:59:59'])->fetch()['total_revenue'] ?? 0;
        
        // Total bookings
        $bookingsQuery = "SELECT COUNT(*) as total_bookings 
                         FROM orders 
                         WHERE created_at BETWEEN ? AND ?";
        $totalBookings = $this->db->query($bookingsQuery, [$startDate, $endDate . ' 23:59:59'])->fetch()['total_bookings'];
        
        // New users
        $usersQuery = "SELECT COUNT(*) as new_users 
                      FROM users 
                      WHERE created_at BETWEEN ? AND ?";
        $newUsers = $this->db->query($usersQuery, [$startDate, $endDate . ' 23:59:59'])->fetch()['new_users'];
        
        // Average booking value
        $avgQuery = "SELECT AVG(total_amount) as avg_booking 
                    FROM orders 
                    WHERE payment_status = 'paid' 
                    AND created_at BETWEEN ? AND ?";
        $avgBooking = $this->db->query($avgQuery, [$startDate, $endDate . ' 23:59:59'])->fetch()['avg_booking'] ?? 0;
        
        // Daily revenue chart data
        $dailyRevenueQuery = "SELECT DATE(created_at) as date, SUM(total_amount) as revenue 
                             FROM orders 
                             WHERE payment_status = 'paid' 
                             AND created_at BETWEEN ? AND ? 
                             GROUP BY DATE(created_at) 
                             ORDER BY date";
        $dailyRevenue = $this->db->query($dailyRevenueQuery, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        // Booking status distribution
        $statusQuery = "SELECT booking_status, COUNT(*) as count 
                       FROM orders 
                       WHERE created_at BETWEEN ? AND ? 
                       GROUP BY booking_status";
        $bookingStatus = $this->db->query($statusQuery, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        return [
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_bookings' => $totalBookings,
                'new_users' => $newUsers,
                'avg_booking' => $avgBooking
            ],
            'daily_revenue' => $dailyRevenue,
            'booking_status' => $bookingStatus
        ];
    }
    
    private function getRevenueReport($startDate, $endDate) {
        // Monthly revenue
        $monthlyQuery = "SELECT DATE_FORMAT(created_at, '%Y-%m') as month, 
                                SUM(total_amount) as revenue,
                                COUNT(*) as bookings
                        FROM orders 
                        WHERE payment_status = 'paid' 
                        AND created_at BETWEEN ? AND ? 
                        GROUP BY DATE_FORMAT(created_at, '%Y-%m') 
                        ORDER BY month";
        $monthlyRevenue = $this->db->query($monthlyQuery, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        // Revenue by room category
        $categoryQuery = "SELECT r.category, SUM(o.total_amount) as revenue, COUNT(*) as bookings
                         FROM orders o 
                         JOIN rooms r ON o.room_id = r.id 
                         WHERE o.payment_status = 'paid' 
                         AND o.created_at BETWEEN ? AND ? 
                         GROUP BY r.category 
                         ORDER BY revenue DESC";
        $categoryRevenue = $this->db->query($categoryQuery, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        // Revenue by city
        $cityQuery = "SELECT r.city, SUM(o.total_amount) as revenue, COUNT(*) as bookings
                     FROM orders o 
                     JOIN rooms r ON o.room_id = r.id 
                     WHERE o.payment_status = 'paid' 
                     AND o.created_at BETWEEN ? AND ? 
                     GROUP BY r.city 
                     ORDER BY revenue DESC";
        $cityRevenue = $this->db->query($cityQuery, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        return [
            'monthly_revenue' => $monthlyRevenue,
            'category_revenue' => $categoryRevenue,
            'city_revenue' => $cityRevenue
        ];
    }
    
    private function getBookingsReport($startDate, $endDate) {
        // Bookings by status
        $statusQuery = "SELECT booking_status, COUNT(*) as count 
                       FROM orders 
                       WHERE created_at BETWEEN ? AND ? 
                       GROUP BY booking_status";
        $bookingsByStatus = $this->db->query($statusQuery, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        // Most popular rooms
        $popularRoomsQuery = "SELECT r.title, r.city, COUNT(*) as bookings, SUM(o.total_amount) as revenue
                             FROM orders o 
                             JOIN rooms r ON o.room_id = r.id 
                             WHERE o.created_at BETWEEN ? AND ? 
                             GROUP BY o.room_id 
                             ORDER BY bookings DESC 
                             LIMIT 10";
        $popularRooms = $this->db->query($popularRoomsQuery, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        // Booking trends by day of week
        $dayTrendsQuery = "SELECT DAYNAME(created_at) as day_name, COUNT(*) as bookings
                          FROM orders 
                          WHERE created_at BETWEEN ? AND ? 
                          GROUP BY DAYOFWEEK(created_at), DAYNAME(created_at) 
                          ORDER BY DAYOFWEEK(created_at)";
        $dayTrends = $this->db->query($dayTrendsQuery, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        return [
            'bookings_by_status' => $bookingsByStatus,
            'popular_rooms' => $popularRooms,
            'day_trends' => $dayTrends
        ];
    }
    
    private function getUsersReport($startDate, $endDate) {
        // User registrations over time
        $registrationsQuery = "SELECT DATE(created_at) as date, COUNT(*) as registrations 
                              FROM users 
                              WHERE created_at BETWEEN ? AND ? 
                              GROUP BY DATE(created_at) 
                              ORDER BY date";
        $registrations = $this->db->query($registrationsQuery, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        // Active users (users who made bookings)
        $activeUsersQuery = "SELECT COUNT(DISTINCT user_id) as active_users 
                            FROM orders 
                            WHERE created_at BETWEEN ? AND ?";
        $activeUsers = $this->db->query($activeUsersQuery, [$startDate, $endDate . ' 23:59:59'])->fetch()['active_users'];
        
        // Top customers by spending
        $topCustomersQuery = "SELECT u.name, u.email, COUNT(*) as total_bookings, SUM(o.total_amount) as total_spent
                             FROM users u 
                             JOIN orders o ON u.id = o.user_id 
                             WHERE o.payment_status = 'paid' 
                             AND o.created_at BETWEEN ? AND ? 
                             GROUP BY u.id 
                             ORDER BY total_spent DESC 
                             LIMIT 10";
        $topCustomers = $this->db->query($topCustomersQuery, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        return [
            'registrations' => $registrations,
            'active_users' => $activeUsers,
            'top_customers' => $topCustomers
        ];
    }
    
    private function getRoomsReport($startDate, $endDate) {
        // Room performance
        $performanceQuery = "SELECT r.id, r.title, r.city, r.category, r.price_per_month,
                                   COUNT(o.id) as total_bookings,
                                   SUM(CASE WHEN o.payment_status = 'paid' THEN o.total_amount ELSE 0 END) as revenue,
                                   AVG(CASE WHEN rev.rating IS NOT NULL THEN rev.rating ELSE 0 END) as avg_rating
                            FROM rooms r 
                            LEFT JOIN orders o ON r.id = o.room_id AND o.created_at BETWEEN ? AND ?
                            LEFT JOIN reviews rev ON r.id = rev.room_id AND rev.status = 'approved'
                            GROUP BY r.id 
                            ORDER BY total_bookings DESC";
        $roomPerformance = $this->db->query($performanceQuery, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        // Occupancy rates
        $occupancyQuery = "SELECT availability_status, COUNT(*) as count 
                          FROM rooms 
                          GROUP BY availability_status";
        $occupancyRates = $this->db->query($occupancyQuery)->fetchAll();
        
        return [
            'room_performance' => $roomPerformance,
            'occupancy_rates' => $occupancyRates
        ];
    }
    
    public function export() {
        $this->requireAdmin();
        
        $reportType = $_GET['report_type'] ?? 'overview';
        $dateRange = $_GET['date_range'] ?? '30';
        $format = $_GET['format'] ?? 'csv';
        
        $endDate = date('Y-m-d');
        $startDate = date('Y-m-d', strtotime("-{$dateRange} days"));
        
        if ($format === 'csv') {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $reportType . '_report_' . date('Y-m-d') . '.csv"');
            
            $output = fopen('php://output', 'w');
            
            switch ($reportType) {
                case 'revenue':
                    $this->exportRevenueCSV($output, $startDate, $endDate);
                    break;
                case 'bookings':
                    $this->exportBookingsCSV($output, $startDate, $endDate);
                    break;
                case 'users':
                    $this->exportUsersCSV($output, $startDate, $endDate);
                    break;
                case 'rooms':
                    $this->exportRoomsCSV($output, $startDate, $endDate);
                    break;
                default:
                    $this->exportOverviewCSV($output, $startDate, $endDate);
            }
            
            fclose($output);
            exit;
        }
    }
    
    private function exportRevenueCSV($output, $startDate, $endDate) {
        fputcsv($output, ['Date', 'Revenue', 'Bookings']);
        
        $query = "SELECT DATE(created_at) as date, SUM(total_amount) as revenue, COUNT(*) as bookings
                 FROM orders 
                 WHERE payment_status = 'paid' 
                 AND created_at BETWEEN ? AND ? 
                 GROUP BY DATE(created_at) 
                 ORDER BY date";
        
        $data = $this->db->query($query, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        foreach ($data as $row) {
            fputcsv($output, [$row['date'], $row['revenue'], $row['bookings']]);
        }
    }
    
    private function exportBookingsCSV($output, $startDate, $endDate) {
        fputcsv($output, ['Booking ID', 'User', 'Room', 'Start Date', 'End Date', 'Amount', 'Status', 'Created']);
        
        $query = "SELECT o.id, u.name, r.title, o.start_date, o.end_date, o.total_amount, o.booking_status, o.created_at
                 FROM orders o 
                 JOIN users u ON o.user_id = u.id 
                 JOIN rooms r ON o.room_id = r.id 
                 WHERE o.created_at BETWEEN ? AND ? 
                 ORDER BY o.created_at DESC";
        
        $data = $this->db->query($query, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        foreach ($data as $row) {
            fputcsv($output, [
                $row['id'], $row['name'], $row['title'], 
                $row['start_date'], $row['end_date'], $row['total_amount'], 
                $row['booking_status'], $row['created_at']
            ]);
        }
    }
    
    private function exportUsersCSV($output, $startDate, $endDate) {
        fputcsv($output, ['User ID', 'Name', 'Email', 'Phone', 'Total Bookings', 'Total Spent', 'Registered']);
        
        $query = "SELECT u.id, u.name, u.email, u.phone, 
                        COUNT(o.id) as total_bookings,
                        SUM(CASE WHEN o.payment_status = 'paid' THEN o.total_amount ELSE 0 END) as total_spent,
                        u.created_at
                 FROM users u 
                 LEFT JOIN orders o ON u.id = o.user_id 
                 WHERE u.created_at BETWEEN ? AND ? 
                 GROUP BY u.id 
                 ORDER BY u.created_at DESC";
        
        $data = $this->db->query($query, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        foreach ($data as $row) {
            fputcsv($output, [
                $row['id'], $row['name'], $row['email'], $row['phone'],
                $row['total_bookings'], $row['total_spent'], $row['created_at']
            ]);
        }
    }
    
    private function exportRoomsCSV($output, $startDate, $endDate) {
        fputcsv($output, ['Room ID', 'Title', 'City', 'Category', 'Price', 'Total Bookings', 'Revenue', 'Status']);
        
        $query = "SELECT r.id, r.title, r.city, r.category, r.price_per_month,
                        COUNT(o.id) as total_bookings,
                        SUM(CASE WHEN o.payment_status = 'paid' THEN o.total_amount ELSE 0 END) as revenue,
                        r.status
                 FROM rooms r 
                 LEFT JOIN orders o ON r.id = o.room_id AND o.created_at BETWEEN ? AND ?
                 GROUP BY r.id 
                 ORDER BY total_bookings DESC";
        
        $data = $this->db->query($query, [$startDate, $endDate . ' 23:59:59'])->fetchAll();
        
        foreach ($data as $row) {
            fputcsv($output, [
                $row['id'], $row['title'], $row['city'], $row['category'],
                $row['price_per_month'], $row['total_bookings'], $row['revenue'], $row['status']
            ]);
        }
    }
    
    private function exportOverviewCSV($output, $startDate, $endDate) {
        $data = $this->getOverviewReport($startDate, $endDate);
        
        fputcsv($output, ['Metric', 'Value']);
        fputcsv($output, ['Total Revenue', $data['summary']['total_revenue']]);
        fputcsv($output, ['Total Bookings', $data['summary']['total_bookings']]);
        fputcsv($output, ['New Users', $data['summary']['new_users']]);
        fputcsv($output, ['Average Booking Value', $data['summary']['avg_booking']]);
        
        fputcsv($output, []);
        fputcsv($output, ['Date', 'Daily Revenue']);
        
        foreach ($data['daily_revenue'] as $row) {
            fputcsv($output, [$row['date'], $row['revenue']]);
        }
    }
}
?>
