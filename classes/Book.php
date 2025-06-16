<?php
require_once __DIR__ . '/../config/database.php';

class Book {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function getAllBooks($limit = 50, $offset = 0, $search = '', $category = '', $author = '', $type = '') {
        $sql = "SELECT b.*, a.name as author_name, c.name as category_name, p.name as publisher_name 
                FROM books b 
                LEFT JOIN authors a ON b.author_id = a.id 
                LEFT JOIN categories c ON b.category_id = c.id 
                LEFT JOIN publishers p ON b.publisher_id = p.id 
                WHERE 1=1";
        $params = [];
        
        if ($search) {
            $sql .= " AND (b.title LIKE ? OR b.isbn LIKE ? OR a.name LIKE ?)";
            $searchParam = "%$search%";
            $params = array_merge($params, [$searchParam, $searchParam, $searchParam]);
        }
        
        if ($category) {
            $sql .= " AND b.category_id = ?";
            $params[] = $category;
        }
        
        if ($author) {
            $sql .= " AND b.author_id = ?";
            $params[] = $author;
        }
        
        if ($type) {
            $sql .= " AND b.type = ?";
            $params[] = $type;
        }
        
        $sql .= " ORDER BY b.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getTotalCount($search = '', $category = '', $author = '', $type = '') {
        $sql = "SELECT COUNT(*) FROM books b 
               LEFT JOIN authors a ON b.author_id = a.id 
               WHERE 1=1";
        $params = [];
        
        if ($search) {
            $sql .= " AND (b.title LIKE ? OR b.isbn LIKE ? OR a.name LIKE ?)";
            $searchParam = "%$search%";
            $params = array_merge($params, [$searchParam, $searchParam, $searchParam]);
        }
        
        if ($category) {
            $sql .= " AND b.category_id = ?";
            $params[] = $category;
        }
        
        if ($author) {
            $sql .= " AND b.author_id = ?";
            $params[] = $author;
        }
        
        if ($type) {
            $sql .= " AND b.type = ?";
            $params[] = $type;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }
    
    public function getBookById($id) {
        $stmt = $this->db->prepare("
            SELECT b.*, a.name as author_name, c.name as category_name, p.name as publisher_name 
            FROM books b 
            LEFT JOIN authors a ON b.author_id = a.id 
            LEFT JOIN categories c ON b.category_id = c.id 
            LEFT JOIN publishers p ON b.publisher_id = p.id 
            WHERE b.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function addBook($data) {
        $stmt = $this->db->prepare("
            INSERT INTO books (isbn, title, subtitle, type, author_id, publisher_id, category_id, 
                             publication_date, pages, language, edition, description, 
                             cover_image, total_copies, available_copies, location, price) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['isbn'], 
            $data['title'], 
            $data['subtitle'] ?? null, 
            $data['type'], 
            $data['author_id'] ?: null,
            $data['publisher_id'] ?: null, 
            $data['category_id'] ?: null,
            $data['publication_date'] ?: null, 
            $data['pages'] ?: null, 
            $data['language'] ?: 'English',
            $data['edition'] ?: null, 
            $data['description'] ?: null, 
            $data['cover_image'] ?: null,
            $data['total_copies'] ?: 1, 
            $data['total_copies'] ?: 1, 
            $data['location'] ?: null, 
            $data['price'] ?: null
        ]);
    }
    
    public function updateBook($id, $data) {
        $stmt = $this->db->prepare("
            UPDATE books SET 
                isbn = ?, 
                title = ?, 
                subtitle = ?, 
                type = ?,
                author_id = ?, 
                publisher_id = ?, 
                category_id = ?,
                publication_date = ?, 
                pages = ?, 
                language = ?,
                edition = ?, 
                description = ?, 
                cover_image = COALESCE(?, cover_image),
                total_copies = ?, 
                location = ?, 
                price = ?,
                updated_at = NOW()
            WHERE id = ?
        ");
        
        return $stmt->execute([
            $data['isbn'], 
            $data['title'], 
            $data['subtitle'] ?? null, 
            $data['type'],
            $data['author_id'] ?: null, 
            $data['publisher_id'] ?: null, 
            $data['category_id'] ?: null,
            $data['publication_date'] ?: null, 
            $data['pages'] ?: null, 
            $data['language'] ?: 'English',
            $data['edition'] ?: null, 
            $data['description'] ?: null, 
            $data['cover_image'] ?: null,
            $data['total_copies'] ?: 1, 
            $data['location'] ?: null, 
            $data['price'] ?: null,
            $id
        ]);
    }
    
    public function deleteBook($id) {
        $stmt = $this->db->prepare("DELETE FROM books WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function updateAvailableCopies($id, $change) {
        $stmt = $this->db->prepare("
            UPDATE books 
            SET available_copies = available_copies + ?, 
                updated_at = NOW() 
            WHERE id = ?
        ");
        return $stmt->execute([$change, $id]);
    }
    
    public function getCategories() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAuthors() {
        $stmt = $this->db->query("SELECT * FROM authors ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPublishers() {
        $stmt = $this->db->query("SELECT * FROM publishers ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function addCategory($name, $description = '') {
        $stmt = $this->db->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        return $stmt->execute([$name, $description]);
    }
    
    public function addAuthor($name, $bio = '') {
        $stmt = $this->db->prepare("INSERT INTO authors (name, bio) VALUES (?, ?)");
        return $stmt->execute([$name, $bio]);
    }
    
    public function addPublisher($name, $location = '') {
        $stmt = $this->db->prepare("INSERT INTO publishers (name, location) VALUES (?, ?)");
        return $stmt->execute([$name, $location]);
    }
}
?>