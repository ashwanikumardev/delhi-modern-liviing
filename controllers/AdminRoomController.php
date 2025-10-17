<?php
require_once 'helpers/ImageHelper.php';

class AdminRoomController extends Controller {
    
    public function index() {
        $this->requireAdmin();
        
        $room = new Room();
        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        $status = $_GET['status'] ?? '';
        
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(title LIKE ? OR address LIKE ? OR city LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($category) {
            $conditions[] = "category = ?";
            $params[] = $category;
        }
        
        if ($status) {
            $conditions[] = "status = ?";
            $params[] = $status;
        }
        
        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        // Get rooms with proper SQL query to avoid array access issues
        $sql = "SELECT * FROM rooms $where ORDER BY created_at DESC LIMIT " . ADMIN_ITEMS_PER_PAGE . " OFFSET " . (($page - 1) * ADMIN_ITEMS_PER_PAGE);
        $rooms = $this->db->query($sql, $params)->fetchAll();

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM rooms $where";
        $totalRooms = $this->db->query($countSql, $params)->fetch()['total'];
        $totalPages = ceil($totalRooms / ADMIN_ITEMS_PER_PAGE);

        // Get statistics
        $stats = [
            'total' => $this->db->query("SELECT COUNT(*) as count FROM rooms")->fetch()['count'],
            'active' => $this->db->query("SELECT COUNT(*) as count FROM rooms WHERE status = 'active'")->fetch()['count'],
            'inactive' => $this->db->query("SELECT COUNT(*) as count FROM rooms WHERE status = 'inactive'")->fetch()['count'],
            'available' => $this->db->query("SELECT COUNT(*) as count FROM rooms WHERE availability_status = 'available'")->fetch()['count'],
            'occupied' => $this->db->query("SELECT COUNT(*) as count FROM rooms WHERE availability_status = 'occupied'")->fetch()['count']
        ];
        
        $this->render('admin/rooms/index', [
            'rooms' => $rooms,
            'stats' => $stats,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'category' => $category,
            'status' => $status,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    public function create() {
        $this->requireAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            // Handle image uploads
            $uploadedImages = [];
            if (!empty($_FILES['images']['name'][0])) {
                $uploadResult = ImageHelper::uploadImages($_FILES['images']);
                if ($uploadResult['success']) {
                    $uploadedImages = $uploadResult['files'];
                }
                if (!empty($uploadResult['errors'])) {
                    $this->setFlash('error', 'Image upload errors: ' . implode(', ', $uploadResult['errors']));
                }
            }

            $data = [
                'title' => $this->sanitize($_POST['title']),
                'description' => $this->sanitize($_POST['description']),
                'price_per_month' => (float)($_POST['price'] ?? 0),
                'deposit' => (float)($_POST['security_deposit'] ?? 0),
                'category' => $_POST['category'],
                'address' => $this->sanitize($_POST['address']),
                'city' => $this->sanitize($_POST['city'] ?? 'Delhi'),
                'pincode' => $this->sanitize($_POST['pincode'] ?? '110001'),
                'amenities' => json_encode($_POST['amenities'] ?? []),
                'images' => json_encode($uploadedImages),
                'featured' => isset($_POST['featured']) ? 1 : 0,
                'status' => 'active',
                'availability_status' => 'available'
            ];
            
            $room = new Room();
            if ($room->create($data)) {
                $this->logAdminAction('room_created', "Created room: {$data['title']}");
                $successMessage = 'Room created successfully';
                if (!empty($uploadedImages)) {
                    $successMessage .= ' with ' . count($uploadedImages) . ' image(s)';
                }
                $this->setFlash('success', $successMessage);
                $this->redirect('/admin/rooms');
            } else {
                $this->setFlash('error', 'Failed to create room');
            }
        }
        
        $this->render('admin/rooms/create', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    public function edit($id = null) {
        $this->requireAdmin();

        $id = $id ?? $this->getParam('id');
        $room = new Room();
        $roomData = $room->find($id);
        
        if (!$roomData) {
            $this->setFlash('error', 'Room not found');
            $this->redirect('/admin/rooms');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCsrf();

            // Handle image uploads
            $existingImages = json_decode($roomData['images'] ?? '[]', true);
            $uploadedImages = $existingImages; // Keep existing images

            if (!empty($_FILES['images']['name'][0])) {
                $uploadResult = ImageHelper::uploadImages($_FILES['images']);
                if ($uploadResult['success']) {
                    $uploadedImages = array_merge($uploadedImages, $uploadResult['files']);
                }
                if (!empty($uploadResult['errors'])) {
                    $this->setFlash('error', 'Image upload errors: ' . implode(', ', $uploadResult['errors']));
                }
            }

            $data = [
                'title' => $this->sanitize($_POST['title']),
                'description' => $this->sanitize($_POST['description']),
                'price_per_month' => (float)$_POST['price_per_month'],
                'deposit' => (float)$_POST['deposit'],
                'category' => $_POST['category'],
                'address' => $this->sanitize($_POST['address']),
                'city' => $this->sanitize($_POST['city']),
                'pincode' => $this->sanitize($_POST['pincode']),
                'amenities' => json_encode($_POST['amenities'] ?? []),
                'images' => json_encode($uploadedImages),
                'featured' => isset($_POST['featured']) ? 1 : 0,
                'status' => $_POST['status'] ?? 'active',
                'availability_status' => $_POST['availability_status'] ?? 'available'
            ];
            
            if ($room->update($id, $data)) {
                $this->logAdminAction('room_updated', "Updated room: {$data['title']}");
                $this->setFlash('success', 'Room updated successfully');
                $this->redirect('/admin/rooms');
            } else {
                $this->setFlash('error', 'Failed to update room');
            }
        }
        
        $this->render('admin/rooms/edit', [
            'room' => $roomData,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }
    
    public function delete($id = null) {
        $this->requireAdmin();
        $this->validateCsrf();

        $id = $id ?? $this->getParam('id');
        $room = new Room();
        $roomData = $room->find($id);
        
        if (!$roomData) {
            $this->jsonResponse(['success' => false, 'message' => 'Room not found']);
            return;
        }
        
        if ($room->delete($id)) {
            $this->logAdminAction('room_deleted', "Deleted room: {$roomData['title']}");
            $this->jsonResponse(['success' => true, 'message' => 'Room deleted successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to delete room']);
        }
    }
    
    public function toggleStatus() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        $id = $this->getParam('id');
        $room = new Room();
        $roomData = $room->find($id);
        
        if (!$roomData) {
            $this->jsonResponse(['success' => false, 'message' => 'Room not found']);
            return;
        }
        
        $newStatus = $roomData['status'] === 'active' ? 'inactive' : 'active';
        
        if ($room->update($id, ['status' => $newStatus])) {
            $this->logAdminAction('room_status_changed', "Changed room status: {$roomData['title']} to $newStatus");
            $this->jsonResponse(['success' => true, 'message' => 'Room status updated', 'status' => $newStatus]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to update room status']);
        }
    }
}
?>
