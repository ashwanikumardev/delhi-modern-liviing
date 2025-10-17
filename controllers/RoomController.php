<?php
class RoomController extends Controller {
    private $roomModel;
    
    public function __construct() {
        parent::__construct();
        $this->roomModel = new Room();
    }
    
    public function index() {
        // Get filters from query parameters
        $filters = [
            'category' => $_GET['category'] ?? '',
            'city' => $_GET['city'] ?? '',
            'search' => $_GET['search'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
            'availability' => $_GET['availability'] ?? '',
            'sort' => $_GET['sort'] ?? 'newest'
        ];
        
        $page = (int)($_GET['page'] ?? 1);
        $perPage = ITEMS_PER_PAGE;
        
        // Get rooms with filters
        $result = $this->roomModel->searchRooms($filters, $page, $perPage);
        
        // Get filter options
        $categories = $this->getCategories();
        $cities = $this->getCities();
        $priceRanges = $this->getPriceRanges();
        
        $this->view('rooms/index', [
            'rooms' => $result['rooms'],
            'pagination' => [
                'current_page' => $result['current_page'],
                'last_page' => $result['last_page'],
                'total' => $result['total'],
                'per_page' => $result['per_page']
            ],
            'filters' => $filters,
            'categories' => $categories,
            'cities' => $cities,
            'price_ranges' => $priceRanges
        ]);
    }
    
    public function show($id) {
        // Get room details
        $room = $this->roomModel->find($id);
        
        if (!$room || $room['status'] !== 'active') {
            http_response_code(404);
            $this->view('errors/404');
            return;
        }
        
        // Increment view count
        $this->roomModel->incrementViews($id);
        
        // Get related rooms
        $relatedRooms = $this->roomModel->getRelatedRooms($id, $room['category'], 4);
        
        // Parse JSON fields
        $room['amenities'] = json_decode($room['amenities'], true) ?? [];
        $room['images'] = json_decode($room['images'], true) ?? [];
        
        // Check if user is logged in for cart functionality
        $isLoggedIn = $this->isAuthenticated();
        
        $this->view('rooms/show', [
            'room' => $room,
            'related_rooms' => $relatedRooms,
            'is_logged_in' => $isLoggedIn,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    private function getCategories() {
        $sql = "SELECT category, COUNT(*) as count 
                FROM rooms 
                WHERE status = 'active' 
                GROUP BY category 
                ORDER BY count DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    private function getCities() {
        $sql = "SELECT city, COUNT(*) as count 
                FROM rooms 
                WHERE status = 'active' 
                GROUP BY city 
                ORDER BY count DESC";
        
        return $this->db->fetchAll($sql);
    }
    
    private function getPriceRanges() {
        return [
            ['label' => 'Under ₹5,000', 'min' => 0, 'max' => 5000],
            ['label' => '₹5,000 - ₹10,000', 'min' => 5000, 'max' => 10000],
            ['label' => '₹10,000 - ₹15,000', 'min' => 10000, 'max' => 15000],
            ['label' => '₹15,000 - ₹20,000', 'min' => 15000, 'max' => 20000],
            ['label' => 'Above ₹20,000', 'min' => 20000, 'max' => 999999]
        ];
    }
}
