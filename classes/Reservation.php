<?php
require_once __DIR__ . '/../config/database.php';

class Reservation {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function reserveBook($bookId, $userId) {
        try {
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare("SELECT available_copies FROM books WHERE id = ?");
            $stmt->execute([$bookId]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$book || $book['available_copies'] <= 0) {
                throw new Exception('Book not available for reservation');
            }
            
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM reservations WHERE user_id = ? AND status = 'active'");
            $stmt->execute([$userId]);
            $currentReservations = $stmt->fetchColumn();
            
            $maxReservations = $this->getSetting('max_reservations_per_user', 3);
            if ($currentReservations >= $maxReservations) {
                throw new Exception('Maximum reservation limit reached');
            }
            
            $reservationDate = date('Y-m-d');
            $expiryHours = $this->getSetting('reservation_duration_hours', 48);
            $expiryDate = date('Y-m-d', strtotime("+$expiryHours hours"));
            
            $stmt = $this->db->prepare("
                INSERT INTO reservations (book_id, user_id, reservation_date, expiry_date) 
                VALUES (?, ?, ?, ?)
            ");
            $stmt->execute([$bookId, $userId, $reservationDate, $expiryDate]);
            
            $this->db->commit();
            return ['success' => true, 'message' => 'Book reserved successfully'];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function cancelReservation($reservationId) {
        $stmt = $this->db->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ?");
        return $stmt->execute([$reservationId]);
    }
    
    public function getUserReservations($userId) {
        $stmt = $this->db->prepare("
            SELECT r.*, b.title, b.isbn, a.name as author_name
            FROM reservations r
            JOIN books b ON r.book_id = b.id
            LEFT JOIN authors a ON b.author_id = a.id
            WHERE r.user_id = ?
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllReservations($limit = 50, $offset = 0, $search = '') {
        $sql = "
            SELECT r.*, b.title, b.isbn, 
                   CONCAT(u.first_name, ' ', u.last_name) as user_name,
                   u.student_id
            FROM reservations r
            JOIN books b ON r.book_id = b.id
            JOIN users u ON r.user_id = u.id
            WHERE 1=1
        ";
        $params = [];
        
        if ($search) {
            $sql .= " AND (b.title LIKE ? OR b.isbn LIKE ? OR u.student_id LIKE ?)"; 
            $searchParam = "%$search%";
            $params = array_merge($params, [$searchParam, $searchParam, $searchParam]);
        }
        
        $sql .= " ORDER BY r.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getSetting($key, $default) {
        $stmt = $this->db->prepare("SELECT value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['value'] : $default;
    }
}
?>