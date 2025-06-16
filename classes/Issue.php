<?php
require_once __DIR__ . '/../config/database.php';

class Issue {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function issueBook($bookId, $userId, $issuedBy, $dueDays = 14) {
        try {
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare("SELECT available_copies FROM books WHERE id = ?");
            $stmt->execute([$bookId]);
            $book = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$book || $book['available_copies'] <= 0) {
                throw new Exception('Book not available');
            }
            
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM book_issues WHERE user_id = ? AND status = 'issued'");
            $stmt->execute([$userId]);
            $currentIssues = $stmt->fetchColumn();
            
            $maxBooks = $this->getSetting('max_books_per_user', 5);
            if ($currentIssues >= $maxBooks) {
                throw new Exception('Maximum book limit reached');
            }
            
            $issueDate = date('Y-m-d');
            $dueDate = date('Y-m-d', strtotime("+$dueDays days"));
            
            $stmt = $this->db->prepare("
                INSERT INTO book_issues (book_id, user_id, issue_date, due_date, issued_by) 
                VALUES (?, ?, ?, ?, ?)
            ");
            $stmt->execute([$bookId, $userId, $issueDate, $dueDate, $issuedBy]);
            
            $stmt = $this->db->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE id = ?");
            $stmt->execute([$bookId]);
            
            $this->db->commit();
            return ['success' => true, 'message' => 'Book issued successfully'];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function returnBook($issueId, $returnedTo) {
        try {
            $this->db->beginTransaction();
            
            $stmt = $this->db->prepare("
                SELECT bi.*, b.title, DATEDIFF(CURDATE(), bi.due_date) as days_overdue 
                FROM book_issues bi 
                JOIN books b ON bi.book_id = b.id 
                WHERE bi.id = ? AND bi.status = 'issued'
            ");
            $stmt->execute([$issueId]);
            $issue = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$issue) {
                throw new Exception('Invalid issue record');
            }
            
            $fine = 0;
            if ($issue['days_overdue'] > 0) {
                $finePerDay = $this->getSetting('fine_per_day', 1.00);
                $graceDays = $this->getSetting('late_return_grace_days', 3);
                $chargableDays = max(0, $issue['days_overdue'] - $graceDays);
                $fine = $chargableDays * $finePerDay;
            }
            
            $stmt = $this->db->prepare("
                UPDATE book_issues 
                SET return_date = CURDATE(), 
                    fine_amount = ?, 
                    status = 'returned', 
                    returned_to = ? 
                WHERE id = ?
            ");
            $stmt->execute([$fine, $returnedTo, $issueId]);
            
            if ($fine > 0) {
                $stmt = $this->db->prepare("
                    INSERT INTO fines (user_id, issue_id, amount, reason) 
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([
                    $issue['user_id'], 
                    $issueId, 
                    $fine, 
                    "Late return of: " . $issue['title']
                ]);
            }
            
            $stmt = $this->db->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE id = ?");
            $stmt->execute([$issue['book_id']]);
            
            $this->db->commit();
            return ['success' => true, 'message' => 'Book returned successfully', 'fine' => $fine];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function getAllIssues($limit = 50, $offset = 0, $status = '', $search = '') {
        $sql = "
            SELECT bi.*, b.title as book_title, b.isbn, 
                   CONCAT(u.first_name, ' ', u.last_name) as user_name, u.student_id,
                   DATEDIFF(CURDATE(), bi.due_date) as days_overdue
            FROM book_issues bi
            JOIN books b ON bi.book_id = b.id
            JOIN users u ON bi.user_id = u.id
            WHERE 1=1
        ";
        $params = [];
        
        if ($status) {
            $sql .= " AND bi.status = ?";
            $params[] = $status;
        }
        
        if ($search) {
            $sql .= " AND (b.title LIKE ? OR b.isbn LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ? OR u.student_id LIKE ?)"; 
            $searchParam = "%$search%";
            $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam]);
        }
        
        $sql .= " ORDER BY bi.issue_date DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUserIssues($userId, $status = '') {
        $sql = "
            SELECT bi.*, b.title as book_title, b.isbn, 
                   DATEDIFF(CURDATE(), bi.due_date) as days_overdue
            FROM book_issues bi
            JOIN books b ON bi.book_id = b.id
            WHERE bi.user_id = ?
        ";
        $params = [$userId];
        
        if ($status) {
            $sql .= " AND bi.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY bi.issue_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function getSetting($key, $default = null) {
        $stmt = $this->db->prepare("SELECT value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['value'] : $default;
    }
}
?>