<?php
require_once __DIR__ . '/../config/database.php';

class Migration {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    public function createTables() {
        // Disable foreign key checks
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        
        // Drop existing tables in correct order
        $tables = ['reservations', 'issues', 'books', 'publishers', 'authors', 'categories', 'users', 'settings'];
        foreach ($tables as $table) {
            $this->pdo->exec("DROP TABLE IF EXISTS $table");
        }
        
        // Re-enable foreign key checks
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        $sql = "
        CREATE DATABASE IF NOT EXISTS library_db;
        USE library_db;
        
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            phone VARCHAR(20),
            address TEXT,
            role ENUM('admin', 'librarian', 'student') NOT NULL,
            student_id VARCHAR(20),
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at DATETIME NOT NULL,
            updated_at DATETIME,
            INDEX (email)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME,
            INDEX (name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS authors (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME,
            INDEX (name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS publishers (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME,
            INDEX (name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS books (
            id INT AUTO_INCREMENT PRIMARY KEY,
            isbn VARCHAR(20) NOT NULL,
            title VARCHAR(255) NOT NULL,
            subtitle VARCHAR(255),
            type ENUM('book', 'thesis', 'manual') NOT NULL,
            author_id INT,
            category_id INT,
            publisher_id INT,
            publication_date DATE,
            pages INT,
            language VARCHAR(50),
            edition VARCHAR(50),
            description TEXT,
            total_copies INT NOT NULL,
            available_copies INT NOT NULL,
            location VARCHAR(100),
            price DECIMAL(10,2),
            cover_image VARCHAR(255),
            created_at DATETIME NOT NULL,
            updated_at DATETIME,
            FOREIGN KEY (author_id) REFERENCES authors(id) ON DELETE SET NULL,
            FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL,
            FOREIGN KEY (publisher_id) REFERENCES publishers(id) ON DELETE SET NULL,
            UNIQUE INDEX (isbn)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS issues (
            id INT AUTO_INCREMENT PRIMARY KEY,
            book_id INT NOT NULL,
            user_id INT NOT NULL,
            issue_date DATETIME NOT NULL,
            due_date DATE NOT NULL,
            return_date DATE,
            status ENUM('issued', 'returned') NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME,
            updated_by INT,
            FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS reservations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            book_id INT NOT NULL,
            user_id INT NOT NULL,
            reservation_date DATETIME NOT NULL,
            expiry_date DATE NOT NULL,
            status ENUM('active', 'cancelled', 'fulfilled') NOT NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME,
            FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS fines (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            book_id INT,
            amount DECIMAL(10,2) NOT NULL,
            reason TEXT NOT NULL,
            status ENUM('pending', 'paid', 'waived') NOT NULL,
            payment_method VARCHAR(50),
            created_at DATETIME NOT NULL,
            updated_at DATETIME,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS activities (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            action VARCHAR(100) NOT NULL,
            description TEXT,
            created_at DATETIME NOT NULL,
            updated_at DATETIME,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

        CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(50) NOT NULL UNIQUE,
            setting_value VARCHAR(255) NOT NULL,
            description TEXT,
            created_at DATETIME NOT NULL,
            updated_at DATETIME,
            INDEX (setting_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ";
        
        try {
            $this->pdo->exec($sql);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}