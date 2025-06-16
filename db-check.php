<?php
require_once __DIR__ . '/config/database.php';
$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "Database connection successful<br>";
    
    // Check if the university_library database exists
    $stmt = $conn->query("SHOW DATABASES LIKE 'university_library'");
    if ($stmt->rowCount() > 0) {
        echo "university_library database exists<br>";
        
        // Check if the users table exists
        $stmt = $conn->query("SHOW TABLES FROM university_library LIKE 'users'");
        if ($stmt->rowCount() > 0) {
            echo "users table exists<br>";
            
            // Check if the admin user exists
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute(['admin@university.edu']);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user) {
                echo "Admin user exists<br>";
                echo "User details: <pre>" . print_r($user, true) . "</pre>";
                
                // Check password hash
                echo "Password hash: " . $user['password'] . "<br>";
                echo "Verify password: " . (password_verify('admin123', $user['password']) ? 'true' : 'false') . "<br>";
            } else {
                echo "Admin user does not exist<br>";
            }
        } else {
            echo "users table does not exist<br>";
        }
    } else {
        echo "university_library database does not exist<br>";
    }
} else {
    echo "Database connection failed<br>";
}
?>