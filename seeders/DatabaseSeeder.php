<?php
require_once __DIR__ . '/../config/database.php';

class DatabaseSeeder {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function run() {
        $this->seedUsers();
        $this->seedCategories();
        $this->seedAuthors();
        $this->seedPublishers();
        $this->seedBooks();
        $this->seedSettings();
    }
    
    private function seedUsers() {
        $users = [
            ['Admin', 'User', 'admin@university.edu', password_hash('admin123', PASSWORD_DEFAULT), 'admin', null, 'active', date('Y-m-d H:i:s'), null],
            ['Librarian', 'User', 'librarian@university.edu', password_hash('librarian123', PASSWORD_DEFAULT), 'librarian', null, 'active', date('Y-m-d H:i:s'), null],
            ['Alice', 'Smith', 'alice@university.edu', password_hash('student123', PASSWORD_DEFAULT), 'student', 'STU001', 'active', date('Y-m-d H:i:s'), null]
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO users (first_name, last_name, email, password, role, student_id, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($users as $user) {
            $stmt->execute($user);
        }
    }
    
    private function seedCategories() {
        $categories = [
            ['Fiction', date('Y-m-d H:i:s'), null],
            ['Non-Fiction', date('Y-m-d H:i:s'), null],
            ['Science', date('Y-m-d H:i:s'), null]
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO categories (name, created_at, updated_at) VALUES (?, ?, ?)");
        foreach ($categories as $category) {
            $stmt->execute($category);
        }
    }
    
    private function seedAuthors() {
        $authors = [
            ['Jane Austen', date('Y-m-d H:i:s'), null],
            ['Isaac Asimov', date('Y-m-d H:i:s'), null],
            ['J.K. Rowling', date('Y-m-d H:i:s'), null]
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO authors (name, created_at, updated_at) VALUES (?, ?, ?)");
        foreach ($authors as $author) {
            $stmt->execute($author);
        }
    }
    
    private function seedPublishers() {
        $publishers = [
            ['Penguin Books', date('Y-m-d H:i:s'), null],
            ['Random House', date('Y-m-d H:i:s'), null],
            ['HarperCollins', date('Y-m-d H:i:s'), null]
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO publishers (name, created_at, updated_at) VALUES (?, ?, ?)");
        foreach ($publishers as $publisher) {
            $stmt->execute($publisher);
        }
    }
    
    private function seedBooks() {
        $books = [
            ['9780141439518', 'Pride and Prejudice', null, 'book', 1, 1, 1, '2008-08-01', 464, 'English', '1st', 'A guide to writing clean, readable code', 'clean_code.jpg', 5, 5, 'A-CS-001', 45.99, date('Y-m-d H:i:s'), null],
            ['9780553293357', 'Foundation', null, 'book', 2, 3, 2, '1999-10-20', 352, 'English', '2nd', 'Essential reading for software developers', 'pragmatic.jpg', 3, 3, 'A-CS-002', 39.99, date('Y-m-d H:i:s'), null],
            ['9780747532743', 'Harry Potter', null, 'book', 3, 1, 3, '2004-10-25', 694, 'English', '1st', 'Learning design patterns the easy way', 'head_first.jpg', 6, 6, 'A-CS-004', 48.99, date('Y-m-d H:i:s'), null]
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO books (isbn, title, subtitle, type, author_id, category_id, publisher_id, publication_date, pages, language, edition, description, cover_image, total_copies, available_copies, location, price, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($books as $book) {
            $stmt->execute($book);
        }
    }
    
    private function seedSettings() {
        $settings = [
            ['fine_per_day', '1.00', 'Fine amount per day for overdue books', date('Y-m-d H:i:s'), null],
            ['max_loan_days', '14', 'Maximum loan duration in days', date('Y-m-d H:i:s'), null],
            ['reservation_expiry_days', '7', 'Days until reservation expires', date('Y-m-d H:i:s'), null]
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO settings (setting_key, setting_value, description, created_at, updated_at) VALUES (?, ?, ?, ?, ?)");
        foreach ($settings as $setting) {
            $stmt->execute($setting);
        }
    }
}