<?php
class AdminTicketController extends Controller {
    
    public function index() {
        $this->requireAdmin();
        
        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';
        $priority = $_GET['priority'] ?? '';
        
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(t.subject LIKE ? OR t.message LIKE ? OR u.name LIKE ? OR u.email LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if ($status) {
            $conditions[] = "t.status = ?";
            $params[] = $status;
        }
        
        if ($priority) {
            $conditions[] = "t.priority = ?";
            $params[] = $priority;
        }
        
        $where = $conditions ? 'WHERE ' . implode(' AND ', $conditions) : '';
        
        $sql = "SELECT t.*, u.name as user_name, u.email as user_email,
                       (SELECT COUNT(*) FROM ticket_replies tr WHERE tr.ticket_id = t.id) as reply_count
                FROM tickets t 
                JOIN users u ON t.user_id = u.id 
                $where
                ORDER BY t.created_at DESC 
                LIMIT " . ADMIN_ITEMS_PER_PAGE . " OFFSET " . (($page - 1) * ADMIN_ITEMS_PER_PAGE);
        
        $tickets = $this->db->query($sql, $params)->fetchAll();
        
        $countSql = "SELECT COUNT(*) as total FROM tickets t 
                     JOIN users u ON t.user_id = u.id $where";
        $totalTickets = $this->db->query($countSql, $params)->fetch()['total'];
        $totalPages = ceil($totalTickets / ADMIN_ITEMS_PER_PAGE);
        
        // Get statistics
        $stats = [
            'total' => $this->db->query("SELECT COUNT(*) as total FROM tickets")->fetch()['total'],
            'open' => $this->db->query("SELECT COUNT(*) as total FROM tickets WHERE status = 'open'")->fetch()['total'],
            'in_progress' => $this->db->query("SELECT COUNT(*) as total FROM tickets WHERE status = 'in_progress'")->fetch()['total'],
            'resolved' => $this->db->query("SELECT COUNT(*) as total FROM tickets WHERE status = 'resolved'")->fetch()['total'],
            'closed' => $this->db->query("SELECT COUNT(*) as total FROM tickets WHERE status = 'closed'")->fetch()['total'],
            'high_priority' => $this->db->query("SELECT COUNT(*) as total FROM tickets WHERE priority = 'high'")->fetch()['total']
        ];
        
        $this->render('admin/tickets/index', [
            'tickets' => $tickets,
            'stats' => $stats,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'search' => $search,
            'status' => $status,
            'priority' => $priority
        ]);
    }
    
    public function viewTicket($id) {
        $this->requireAdmin();
        
        $sql = "SELECT t.*, u.name as user_name, u.email as user_email, u.phone as user_phone
                FROM tickets t 
                JOIN users u ON t.user_id = u.id 
                WHERE t.id = ?";
        
        $ticket = $this->db->query($sql, [$id])->fetch();
        
        if (!$ticket) {
            $this->setFlash('error', 'Ticket not found');
            $this->redirect('/admin/tickets');
        }
        
        // Get replies
        $replySql = "SELECT tr.*, u.name as user_name, u.email as user_email
                     FROM ticket_replies tr 
                     JOIN users u ON tr.user_id = u.id 
                     WHERE tr.ticket_id = ? 
                     ORDER BY tr.created_at ASC";
        
        $replies = $this->db->query($replySql, [$id])->fetchAll();
        
        $this->render('admin/tickets/show', [
            'ticket' => $ticket,
            'replies' => $replies
        ]);
    }
    
    public function reply() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        $ticketId = $this->getParam('id');
        $message = $this->sanitize($_POST['message']);
        
        if (empty($message)) {
            $this->setFlash('error', 'Reply message cannot be empty');
            $this->redirect("/admin/tickets/$ticketId");
        }
        
        // Check if ticket exists
        $ticket = $this->db->query("SELECT * FROM tickets WHERE id = ?", [$ticketId])->fetch();
        if (!$ticket) {
            $this->setFlash('error', 'Ticket not found');
            $this->redirect('/admin/tickets');
        }
        
        // Insert reply
        $sql = "INSERT INTO ticket_replies (ticket_id, user_id, message, created_at) 
                VALUES (?, ?, ?, NOW())";
        
        if ($this->db->query($sql, [$ticketId, $_SESSION['user_id'], $message])) {
            // Update ticket status to in_progress if it was open
            if ($ticket['status'] === 'open') {
                $this->db->query("UPDATE tickets SET status = 'in_progress' WHERE id = ?", [$ticketId]);
            }
            
            $this->logAdminAction('ticket_replied', "Replied to ticket #$ticketId");
            $this->setFlash('success', 'Reply added successfully');
        } else {
            $this->setFlash('error', 'Failed to add reply');
        }
        
        $this->redirect("/admin/tickets/$ticketId");
    }
    
    public function updateStatus() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        $id = $this->getParam('id');
        $status = $_POST['status'] ?? '';
        
        $validStatuses = ['open', 'in_progress', 'resolved', 'closed'];
        if (!in_array($status, $validStatuses)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid status']);
            return;
        }
        
        $sql = "UPDATE tickets SET status = ? WHERE id = ?";
        
        if ($this->db->query($sql, [$status, $id])) {
            $this->logAdminAction('ticket_status_updated', "Updated ticket #$id status to $status");
            $this->jsonResponse(['success' => true, 'message' => 'Ticket status updated successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to update ticket status']);
        }
    }
    
    public function updatePriority() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        $id = $this->getParam('id');
        $priority = $_POST['priority'] ?? '';
        
        $validPriorities = ['low', 'medium', 'high', 'urgent'];
        if (!in_array($priority, $validPriorities)) {
            $this->jsonResponse(['success' => false, 'message' => 'Invalid priority']);
            return;
        }
        
        $sql = "UPDATE tickets SET priority = ? WHERE id = ?";
        
        if ($this->db->query($sql, [$priority, $id])) {
            $this->logAdminAction('ticket_priority_updated', "Updated ticket #$id priority to $priority");
            $this->jsonResponse(['success' => true, 'message' => 'Ticket priority updated successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to update ticket priority']);
        }
    }
    
    public function delete() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        $id = $this->getParam('id');
        
        // Check if ticket exists
        $ticket = $this->db->query("SELECT * FROM tickets WHERE id = ?", [$id])->fetch();
        if (!$ticket) {
            $this->jsonResponse(['success' => false, 'message' => 'Ticket not found']);
            return;
        }
        
        // Delete ticket (replies will be deleted due to foreign key constraint)
        if ($this->db->query("DELETE FROM tickets WHERE id = ?", [$id])) {
            $this->logAdminAction('ticket_deleted', "Deleted ticket #$id: {$ticket['subject']}");
            $this->jsonResponse(['success' => true, 'message' => 'Ticket deleted successfully']);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to delete ticket']);
        }
    }
    
    public function bulkAction() {
        $this->requireAdmin();
        $this->validateCsrf();
        
        $action = $_POST['action'] ?? '';
        $ticketIds = $_POST['ticket_ids'] ?? [];
        
        if (empty($ticketIds) || !is_array($ticketIds)) {
            $this->jsonResponse(['success' => false, 'message' => 'No tickets selected']);
            return;
        }
        
        $placeholders = str_repeat('?,', count($ticketIds) - 1) . '?';
        
        switch ($action) {
            case 'mark_resolved':
                $sql = "UPDATE tickets SET status = 'resolved' WHERE id IN ($placeholders)";
                $message = 'Tickets marked as resolved';
                break;
                
            case 'mark_closed':
                $sql = "UPDATE tickets SET status = 'closed' WHERE id IN ($placeholders)";
                $message = 'Tickets marked as closed';
                break;
                
            case 'delete':
                $sql = "DELETE FROM tickets WHERE id IN ($placeholders)";
                $message = 'Tickets deleted successfully';
                break;
                
            default:
                $this->jsonResponse(['success' => false, 'message' => 'Invalid action']);
                return;
        }
        
        if ($this->db->query($sql, $ticketIds)) {
            $this->logAdminAction('tickets_bulk_action', "Performed bulk action '$action' on " . count($ticketIds) . " tickets");
            $this->jsonResponse(['success' => true, 'message' => $message]);
        } else {
            $this->jsonResponse(['success' => false, 'message' => 'Failed to perform bulk action']);
        }
    }
}
?>
