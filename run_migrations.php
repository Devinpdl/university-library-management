<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/migrations/create_tables.php';

echo "<h1>University Library Management System - Database Setup</h1>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    echo "<p>Database connection successful!</p>";
    
    $migration = new Migration($conn);
    $result = $migration->createTables();
    
    if ($result) {
        echo "<p style='color: green;'>Tables created successfully!</p>";
    } else {
        echo "<p style='color: red;'>Error creating tables. Check your database configuration.</p>";
    }
    
    echo "<p><a href='index.php'>Go to Homepage</a></p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>Connection error: " . $e->getMessage() . "</p>";
    echo "<p>Please make sure your database server is running and the database credentials are correct in config/database.php</p>";
}
?>