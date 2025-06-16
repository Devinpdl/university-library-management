<?php
// Display any errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database configuration
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'library_db';

try {
    // Connect to MySQL server
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Database Setup</h2>";
    
    // Check if database exists
    $stmt = $conn->query("SHOW DATABASES LIKE '$dbname'");
    $dbExists = $stmt->rowCount() > 0;
    
    if (!$dbExists) {
        // Create database
        $conn->exec("CREATE DATABASE `$dbname`");
        echo "<p>Database '$dbname' created successfully</p>";
    } else {
        echo "<p>Database '$dbname' already exists</p>";
    }
    
    // Connect to the database
    $conn->exec("USE `$dbname`");
    
    // Check if users table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'users'");
    $tableExists = $stmt->rowCount() > 0;
    
    if (!$tableExists) {
        // Create users table
        $sql = "CREATE TABLE `users` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `first_name` VARCHAR(50) NOT NULL,
            `last_name` VARCHAR(50) NOT NULL,
            `email` VARCHAR(100) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `role` ENUM('admin', 'librarian', 'student', 'faculty') NOT NULL,
            `student_id` VARCHAR(20) NULL,
            `phone` VARCHAR(20) NULL,
            `address` TEXT NULL,
            `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `email` (`email`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $conn->exec($sql);
        echo "<p>Table 'users' created successfully</p>";
        
        // Create admin user
        $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `role`, `status`) 
                VALUES ('Admin', 'User', 'admin@university.edu', '$adminPassword', 'admin', 'active')";
        $conn->exec($sql);
        echo "<p>Admin user created successfully</p>";
        
        // Create librarian user
        $librarianPassword = password_hash('lib123', PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `role`, `status`) 
                VALUES ('Librarian', 'User', 'librarian@university.edu', '$librarianPassword', 'librarian', 'active')";
        $conn->exec($sql);
        echo "<p>Librarian user created successfully</p>";
        
        // Create student user
        $studentPassword = password_hash('student123', PASSWORD_DEFAULT);
        $sql = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `role`, `student_id`, `status`) 
                VALUES ('Alice', 'Smith', 'alice@university.edu', '$studentPassword', 'student', 'STU001', 'active')";
        $conn->exec($sql);
        echo "<p>Student user created successfully</p>";
    } else {
        echo "<p>Table 'users' already exists</p>";
        
        // Check if admin user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute(['admin@university.edu']);
        $adminExists = $stmt->rowCount() > 0;
        
        if (!$adminExists) {
            // Create admin user
            $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $sql = "INSERT INTO `users` (`first_name`, `last_name`, `email`, `password`, `role`, `status`) 
                    VALUES ('Admin', 'User', 'admin@university.edu', '$adminPassword', 'admin', 'active')";
            $conn->exec($sql);
            echo "<p>Admin user created successfully</p>";
        } else {
            echo "<p>Admin user already exists</p>";
        }
    }
    
    // Check if activity_logs table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'activity_logs'");
    $tableExists = $stmt->rowCount() > 0;
    
    if (!$tableExists) {
        // Create activity_logs table
        $sql = "CREATE TABLE `activity_logs` (
            `id` INT(11) NOT NULL AUTO_INCREMENT,
            `user_id` INT(11) NULL,
            `action` VARCHAR(50) NOT NULL,
            `description` TEXT NULL,
            `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `user_id` (`user_id`),
            CONSTRAINT `activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        
        $conn->exec($sql);
        echo "<p>Table 'activity_logs' created successfully</p>";
    } else {
        echo "<p>Table 'activity_logs' already exists</p>";
    }
    
    echo "<h3>Database setup completed successfully!</h3>";
    echo "<p>You can now <a href='index.php'>login to the system</a> with the following credentials:</p>";
    echo "<ul>";
    echo "<li>Admin: admin@university.edu / admin123</li>";
    echo "<li>Librarian: librarian@university.edu / lib123</li>";
    echo "<li>Student: alice@university.edu / student123</li>";
    echo "</ul>";
    
} catch(PDOException $e) {
    echo "<h3>Error:</h3> " . $e->getMessage();
}
?>