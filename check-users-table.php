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
    // Connect to the database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Users Table Structure</h2>";
    
    // Get table structure
    $stmt = $conn->query("DESCRIBE users");
    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['Field']}</td>";
        echo "<td>{$row['Type']}</td>";
        echo "<td>{$row['Null']}</td>";
        echo "<td>{$row['Key']}</td>";
        echo "<td>{$row['Default']}</td>";
        echo "<td>{$row['Extra']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // Check if status column exists
    $stmt = $conn->query("SHOW COLUMNS FROM users LIKE 'status'");
    if ($stmt->rowCount() > 0) {
        echo "<p>Status column exists in the users table.</p>";
    } else {
        echo "<p>Status column does NOT exist in the users table.</p>";
    }
    
    // Check if there are any users in the table
    $stmt = $conn->query("SELECT * FROM users");
    echo "<h2>Users in the Database</h2>";
    if ($stmt->rowCount() > 0) {
        echo "<p>Found {$stmt->rowCount()} users in the database.</p>";
        echo "<table border='1'>";
        echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Role</th><th>Status (if exists)</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['first_name']}</td>";
            echo "<td>{$row['last_name']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "<td>{$row['role']}</td>";
            echo "<td>" . (isset($row['status']) ? $row['status'] : 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No users found in the database.</p>";
    }
    
} catch(PDOException $e) {
    echo "<h3>Error:</h3> " . $e->getMessage();
}
?>