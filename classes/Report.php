<?php
require_once __DIR__ . '/../config/database.php';

class Report {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function getDashboardStats() {
        $stats = [];
        
        $stmt = $this->db->query("SELECT COUNT(*) FROM books");
        $stats['total_books'] = $stmt->fetchColumn();
        
        $stmt = $this->db->query("SELECT SUM(available_copies) FROM books");
        $stats['available_books'] = $stmt->fetchColumn() ?: 0;
        
        $stmt = $this->db->query("SELECT COUNT(*) FROM book_issues WHERE status = 'issued'");
        $stats['issued_books'] = $stmt->fetchColumn();
        
        $stmt = $this->db->query("SELECT COUNT(*) FROM users");
        $stats['total_users'] = $stmt->fetchColumn();
        
        // Get recent issues
        $stmt = $this->db->query("
            SELECT bi.issue_date, bi.due_date, b.title, CONCAT(u.first_name, ' ', u.last_name) as user_name
            FROM book_issues bi
            JOIN books b ON bi.book_id = b.id
            JOIN users u ON bi.user_id = u.id
            WHERE bi.status = 'issued'
            ORDER BY bi.issue_date DESC
            LIMIT 5
        ");
        $stats['recent_issues'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get popular books
        $stmt = $this->db->query("
            SELECT b.title, a.name as author, c.name as category, COUNT(bi.id) as issue_count
            FROM books b
            JOIN authors a ON b.author_id = a.id
            JOIN categories c ON b.category_id = c.id
            LEFT JOIN book_issues bi ON b.id = bi.book_id
            GROUP BY b.id
            ORDER BY issue_count DESC
            LIMIT 5
        ");
        $stats['popular_books'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $stats;
    }
    
    public function getRecentActivities($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT al.*, 
                   CONCAT(u.first_name, ' ', u.last_name) as user_name
            FROM activity_logs al
            LEFT JOIN users u ON al.user_id = u.id
            ORDER BY al.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getOverdueReport($limit = 50, $offset = 0) {
        $stmt = $this->db->prepare("
            SELECT bi.*, b.title, b.isbn, 
                   CONCAT(u.first_name, ' ', u.last_name) as user_name,
                   u.student_id,
                   DATEDIFF(CURDATE(), bi.due_date) as days_overdue
            FROM book_issues bi
            JOIN books b ON bi.book_id = b.id
            JOIN users u ON bi.user_id = u.id
            WHERE bi.status = 'issued' AND bi.due_date < CURDATE()
            ORDER BY bi.due_date ASC
            LIMIT ? OFFSET ?
        ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>