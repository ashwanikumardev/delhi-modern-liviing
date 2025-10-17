<?php
class HomeController extends Controller {
    private $roomModel;
    
    public function __construct() {
        parent::__construct();
        try {
            $this->roomModel = new Room();
        } catch (Exception $e) {
            // If database connection fails, roomModel will be null
            $this->roomModel = null;
        }
    }
    
    public function index() {
        try {
            // Get featured rooms
            $featuredRooms = $this->roomModel ? $this->roomModel->getFeaturedRooms(6) : [];
            
            // Get room categories with counts
            $categories = $this->getRoomCategories();
        } catch (Exception $e) {
            // If database fails, use empty arrays
            error_log("Database error in HomeController: " . $e->getMessage());
            $featuredRooms = [];
            $categories = [];
        }
        
        // Get testimonials (you can create a testimonials table later)
        $testimonials = $this->getTestimonials();
        
        // Get FAQs
        $faqs = $this->getFAQs();
        
        $this->view('home/index', [
            'featured_rooms' => $featuredRooms,
            'categories' => $categories,
            'testimonials' => $testimonials,
            'faqs' => $faqs
        ]);
    }
    
    public function about() {
        $this->view('pages/about', [
            'title' => 'About Us - Delhi Modern Living'
        ]);
    }
    
    public function contact() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleContactForm();
            return;
        }
        
        $this->view('pages/contact', [
            'title' => 'Contact Us - Delhi Modern Living'
        ]);
    }
    
    private function handleContactForm() {
        $name = $this->sanitize($_POST['name'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');
        $phone = $this->sanitize($_POST['phone'] ?? '');
        $message = $this->sanitize($_POST['message'] ?? '');
        
        // Basic validation
        if (empty($name) || empty($email) || empty($message)) {
            $_SESSION['flash_error'] = 'Please fill in all required fields.';
            $this->redirect('/contact');
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_error'] = 'Please enter a valid email address.';
            $this->redirect('/contact');
            return;
        }
        
        // In a real application, you would save this to database or send email
        // For now, just show success message
        $_SESSION['flash_success'] = 'Thank you for your message! We will get back to you soon.';
        $this->redirect('/contact');
    }
    
    private function getRoomCategories() {
        try {
            $sql = "SELECT category, COUNT(*) as count, MIN(price_per_month) as min_price 
                    FROM rooms 
                    WHERE status = 'active' 
                    GROUP BY category";
            
            return $this->db->query($sql)->fetchAll();
        } catch (Exception $e) {
            // Return empty array if database query fails
            return [];
        }
    }
    
    private function getTestimonials() {
        // Sample testimonials - you can move this to database later
        return [
            [
                'name' => 'Priya Sharma',
                'rating' => 5,
                'text' => 'Amazing PG with all modern amenities. The staff is very helpful and the location is perfect for working professionals.',
                'image' => '/assets/images/testimonials/user1.jpg'
            ],
            [
                'name' => 'Rahul Kumar',
                'rating' => 5,
                'text' => 'Clean rooms, good food, and excellent security. Highly recommended for students and working people.',
                'image' => '/assets/images/testimonials/user2.jpg'
            ],
            [
                'name' => 'Anita Gupta',
                'rating' => 4,
                'text' => 'Great experience staying here. The booking process was smooth and the room was exactly as shown in photos.',
                'image' => '/assets/images/testimonials/user3.jpg'
            ]
        ];
    }
    
    private function getFAQs() {
        // Sample FAQs - you can move this to database later
        return [
            [
                'question' => 'What is included in the monthly rent?',
                'answer' => 'Monthly rent includes accommodation, electricity, water, WiFi, housekeeping, and basic amenities. Food charges are separate where applicable.'
            ],
            [
                'question' => 'How do I book a room?',
                'answer' => 'You can browse available rooms, select your preferred dates, add to cart, and complete the booking with online payment. You will receive a booking confirmation via email.'
            ],
            [
                'question' => 'What is the security deposit?',
                'answer' => 'Security deposit varies by room type and is refundable at the time of checkout, subject to room condition and any damages.'
            ],
            [
                'question' => 'Can I cancel my booking?',
                'answer' => 'Yes, you can cancel your booking as per our cancellation policy. Refund amount depends on the cancellation timing and room type.'
            ],
            [
                'question' => 'Are meals provided?',
                'answer' => 'Meals are provided in PG accommodations. For independent rooms, you may have access to a shared kitchen or can opt for meal plans.'
            ],
            [
                'question' => 'Is there parking available?',
                'answer' => 'Most of our properties have parking facilities. Please check the room details or contact us for specific parking availability.'
            ]
        ];
    }
}
