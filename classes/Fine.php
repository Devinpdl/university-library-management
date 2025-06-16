<?php
require_once __DIR__ . '/../config/database.php';

class Fine {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function addFine($userId, $issueId, $amount, $reason) {
        $stmt = $this->db->prepare("
            INSERT INTO fines (user_id, issue_id, amount, reason) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $issueId, $amount, $reason]);
    }
    
    public function payFine($fineId, $paymentMethod) {
        $stmt = $this->db->prepare("
            UPDATE fines 
            SET status = 'paid', 
                payment_date = CURDATE(), 
                payment_method = ? 
            WHERE id = ?
        ");
        return $stmt->execute([$paymentMethod, $fineId]);
    }
    
    public function waiveFine($fineId) {
        $stmt = $this->db->prepare("UPDATE fines SET status = 'waived' WHERE id = ?");
        return $stmt->execute([$fineId]);
    }
    
    public function getUserFines($userId) {
        $stmt = $this->db->prepare("
            SELECT f.*, b.title, bi.issue_date
            FROM fines f
            LEFT JOIN book_issues bi ON f.issue_id = bi.id
            LEFT JOIN books b ON bi.book_id = b.id
            WHERE f.user_id = ?
            ORDER BY f.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAllFines($limit = 50, $offset = 0, $search = '') {
        $sql = "
            SELECT f.*, 
                   CONCAT(u.first_name, ' ', u.last_name) as user_name,
                   u.student_id,
                   b.title
            FROM fines f
            JOIN users u ON f.user_id = u.id
            LEFT JOIN book_issues bi ON f.issue_id = bi.id
            LEFT JOIN books b ON bi.book_id = b.id
            WHERE 1=1
        ";
        $params = [];
        
        if ($search) {
            $sql .= " AND (u.student_id LIKE ? OR b.title LIKE ?)"; 
            $searchParam = "%$search%";
            $params = array_merge($params, [$searchParam, $searchParam]);
        }
        
        $sql .= " ORDER BY f.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>