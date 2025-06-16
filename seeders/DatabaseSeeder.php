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
            ['Admin', 'System', 'admin@university.edu', password_hash('admin123', PASSWORD_DEFAULT), 'admin', 'ADM001'],
            ['John', 'Librarian', 'librarian@university.edu', password_hash('lib123', PASSWORD_DEFAULT), 'librarian', 'LIB001'],
            ['Alice', 'Johnson', 'alice@university.edu', password_hash('student123', PASSWORD_DEFAULT), 'student', 'STU001'],
            ['Bob', 'Smith', 'bob@university.edu', password_hash('student123', PASSWORD_DEFAULT), 'student', 'STU002'],
            ['Dr. Emma', 'Wilson', 'emma@university.edu', password_hash('faculty123', PASSWORD_DEFAULT), 'faculty', 'FAC001']
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO users (first_name, last_name, email, password, role, student_id) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($users as $user) {
            $stmt->execute($user);
        }
    }
    
    private function seedCategories() {
        $categories = [
            ['Computer Science', 'Books related to computer science and programming'],
            ['Mathematics', 'Mathematical theories, calculus, algebra, and statistics'],
            ['Physics', 'Physics principles, quantum mechanics, and thermodynamics'],
            ['Literature', 'Classic and modern literature, poetry, and drama'],
            ['History', 'World history, ancient civilizations, and historical events'],
            ['Biology', 'Life sciences, anatomy, genetics, and ecology'],
            ['Chemistry', 'Chemical reactions, organic and inorganic chemistry'],
            ['Engineering', 'Mechanical, electrical, civil, and software engineering'],
            ['Psychology', 'Human behavior, cognitive science, and mental health'],
            ['Business', 'Management, economics, finance, and entrepreneurship']
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        foreach ($categories as $category) {
            $stmt->execute($category);
        }
    }
    
    private function seedAuthors() {
        $authors = [
            ['Robert C. Martin', 'Software engineer and author', 'American', '1952-12-05'],
            ['Donald E. Knuth', 'Computer scientist and mathematician', 'American', '1938-01-10'],
            ['Martin Fowler', 'Software developer and author', 'British', '1963-12-18'],
            ['Gang of Four', 'Erich Gamma, Richard Helm, Ralph Johnson, John Vlissides', 'Various', '1960-01-01'],
            ['Stephen King', 'Horror and suspense novelist', 'American', '1947-09-21'],
            ['Isaac Newton', 'Physicist and mathematician', 'English', '1643-01-04'],
            ['Charles Darwin', 'Naturalist and biologist', 'English', '1809-02-12'],
            ['Albert Einstein', 'Theoretical physicist', 'German', '1879-03-14']
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO authors (name, biography, nationality, birth_date) VALUES (?, ?, ?, ?)");
        foreach ($authors as $author) {
            $stmt->execute($author);
        }
    }
    
    private function seedPublishers() {
        $publishers = [
            ['Prentice Hall', '1 Lake Street, Upper Saddle River, NJ', 'info@prenhall.com', '+1-800-282-0693'],
            ['McGraw-Hill', '2 Penn Plaza, New York, NY', 'info@mheducation.com', '+1-800-338-3987'],
            ['Pearson', '221 River Street, Hoboken, NJ', 'info@pearson.com', '+1-800-848-9500'],
            ['Wiley', '111 River Street, Hoboken, NJ', 'info@wiley.com', '+1-800-225-5945'],
            ['Cambridge University Press', 'University Printing House, Cambridge', 'info@cambridge.org', '+44-1223-358331']
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO publishers (name, address, email, phone) VALUES (?, ?, ?, ?)");
        foreach ($publishers as $publisher) {
            $stmt->execute($publisher);
        }
    }
    
    private function seedBooks() {
        $books = [
            ['9780134685991', 'Clean Code', 'A Handbook of Agile Software Craftsmanship', 'book', 1, 1, 1, '2008-08-01', 464, 'English', '1st', 'A guide to writing clean, readable code', 'clean_code.jpg', 5, 5, 'A-CS-001', 45.99],
            ['9780201616224', 'The Pragmatic Programmer', 'Your Journey to Mastery', 'book', 2, 2, 1, '1999-10-20', 352, 'English', '2nd', 'Essential reading for software developers', 'pragmatic.jpg', 3, 3, 'A-CS-002', 39.99],
            ['9780321356680', 'Effective Java', 'Programming Language Guide', 'book', 3, 3, 1, '2017-12-27', 416, 'English', '3rd', 'Best practices for Java programming', 'effective_java.jpg', 4, 4, 'A-CS-003', 52.99],
            ['9780596007126', 'Head First Design Patterns', 'Building Extensible and Maintainable Object-Oriented Software', 'book', 4, 4, 1, '2004-10-25', 694, 'English', '1st', 'Learning design patterns the easy way', 'head_first.jpg', 6, 6, 'A-CS-004', 48.99],
            ['9780142000694', 'The Great Gatsby', 'Classic American Literature', 'book', 5, 5, 4, '1925-04-10', 180, 'English', 'Scribner', 'A classic of American literature', 'gatsby.jpg', 8, 8, 'B-LIT-001', 12.99],
            ['THS001', 'Advanced AI Algorithms', 'Machine Learning Thesis', 'thesis', 3, 3, 1, '2023-05-15', 250, 'English', '1st', 'Research on AI optimization', 'ai_thesis.pdf', 1, 1, 'T-AI-001', 0.00],
            ['MAN001', 'Library System Manual', 'User Guide', 'manual', 1, 1, 1, '2024-01-01', 100, 'English', '1st', 'User manual for library system', 'manual.pdf', 2, 2, 'M-SYS-001', 0.00]
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO books (isbn, title, subtitle, type, author_id, publisher_id, category_id, publication_date, pages, language, edition, description, cover_image, total_copies, available_copies, location, price) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($books as $book) {
            $stmt->execute($book);
        }
    }
    
    private function seedSettings() {
        $settings = [
            ['max_books_per_user', '3', 'Maximum number of books a user can borrow at once'],
            ['loan_period_days', '14', 'Default loan period in days'],
            ['fine_per_day', '0.50', 'Fine amount per day for overdue books'],
            ['max_fine_amount', '50.00', 'Maximum fine amount per book'],
            ['reservation_duration_hours', '48', 'How long a reservation is held before expiring'],
            ['max_reservations_per_user', '3', 'Maximum number of active reservations per user'],
            ['library_name', 'University Library', 'Name of the library'],
            ['library_email', 'library@university.edu', 'Contact email for the library'],
            ['library_phone', '+1-555-123-4567', 'Contact phone for the library'],
            ['library_address', '123 University Ave, College Town, CT 12345', 'Physical address of the library'],
            ['library_hours', 'Mon-Fri: 8am-10pm, Sat-Sun: 10am-8pm', 'Operating hours of the library']
        ];
        
        $stmt = $this->pdo->prepare("INSERT INTO settings (setting_key, value, description) VALUES (?, ?, ?)");
        foreach ($settings as $setting) {
            $stmt->execute($setting);
        }
    }
}
?>