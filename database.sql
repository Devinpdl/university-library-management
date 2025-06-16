DROP DATABASE IF EXISTS library_db;
CREATE DATABASE IF NOT EXISTS library_db;
USE library_db;

CREATE TABLE users (
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
    updated_at DATETIME
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME
);

CREATE TABLE authors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME
);

CREATE TABLE publishers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME
);

CREATE TABLE books (
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
    FOREIGN KEY (author_id) REFERENCES authors(id),
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (publisher_id) REFERENCES publishers(id)
);

CREATE TABLE issues (
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
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (updated_by) REFERENCES users(id)
);

CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    user_id INT NOT NULL,
    reservation_date DATETIME NOT NULL,
    expiry_date DATE NOT NULL,
    status ENUM('active', 'cancelled', 'fulfilled') NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    FOREIGN KEY (book_id) REFERENCES books(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE fines (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT,
    amount DECIMAL(10,2) NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('pending', 'paid', 'waived') NOT NULL,
    payment_method VARCHAR(50),
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (book_id) REFERENCES books(id)
);

CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    created_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(50) NOT NULL UNIQUE,
    setting_value VARCHAR(255) NOT NULL,
    description TEXT,
    created_at DATETIME NOT NULL,
    updated_at DATETIME
);

-- Insert sample data with plain text passwords
INSERT INTO users (first_name, last_name, email, password, role, student_id, created_at) VALUES
('Admin', 'User', 'admin@university.edu', 'admin123', 'admin', NULL, NOW()),
('Librarian', 'User', 'librarian@university.edu', 'librarian123', 'librarian', NULL, NOW()),
('Alice', 'Smith', 'alice@university.edu', 'student123', 'student', 'STU001', NOW());

INSERT INTO categories (name, created_at) VALUES
('Fiction', NOW()),
('Non-Fiction', NOW()),
('Science', NOW());

INSERT INTO authors (name, created_at) VALUES
('Jane Austen', NOW()),
('Isaac Asimov', NOW()),
('J.K. Rowling', NOW());

INSERT INTO publishers (name, created_at) VALUES
('Penguin Books', NOW()),
('Random House', NOW()),
('HarperCollins', NOW());

INSERT INTO books (isbn, title, type, author_id, category_id, publisher_id, total_copies, available_copies, created_at) VALUES
('9780141439518', 'Pride and Prejudice', 'book', 1, 1, 1, 5, 5, NOW()),
('9780553293357', 'Foundation', 'book', 2, 3, 2, 3, 3, NOW()),
('9780747532743', 'Harry Potter', 'book', 3, 1, 3, 4, 4, NOW());

INSERT INTO settings (setting_key, setting_value, description, created_at) VALUES
('fine_per_day', '1.00', 'Fine amount per day for overdue books', NOW()),
('max_loan_days', '14', 'Maximum loan duration in days', NOW()),
('reservation_expiry_days', '7', 'Days until reservation expires', NOW());