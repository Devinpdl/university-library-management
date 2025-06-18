<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/seeders/DatabaseSeeder.php';

echo "<h1>University Library Management System - Database Seeding</h1>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    echo "<p>Database connection successful!</p>";
    
    $seeder = new DatabaseSeeder($conn);
    
    echo "<p>Starting database seeding...</p>";
    
    try {
        $seeder->run();
        echo "<p style='color: green;'>Database seeded successfully!</p>";
        echo "<p>The following demo accounts have been created:</p>";
        echo "<ul>";
        echo "<li><strong>Admin:</strong> admin@university.edu / admin123</li>";
        echo "<li><strong>Librarian:</strong> librarian@university.edu / librarian123</li>";
        echo "<li><strong>Student:</strong> alice@university.edu / student123</li>";
        echo "</ul>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>Error seeding database: " . $e->getMessage() . "</p>";
    }
    
    echo "<p><a href='index.php'>Go to Homepage</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Connection error: " . $e->getMessage() . "</p>";
    echo "<p>Please make sure your database server is running and the database credentials are correct in config/database.php</p>";
}