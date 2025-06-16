<?php
require_once __DIR__ . '/classes/Auth.php';

// Display any errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Test login with hardcoded credentials
$auth = new Auth();
$email = 'admin@university.edu';
$password = 'admin123';

echo "<h2>Testing Login</h2>";
echo "Email: $email<br>";
echo "Password: $password<br><br>";

$result = $auth->login($email, $password);

echo "<h3>Login Result:</h3>";
echo "<pre>" . print_r($result, true) . "</pre>";

if (isset($result['success']) && $result['success']) {
    echo "<h3>Login Successful!</h3>";
    echo "<p>Session Variables:</p>";
    echo "<pre>" . print_r($_SESSION, true) . "</pre>";
} else {
    echo "<h3>Login Failed!</h3>";
}

// Check database connection
require_once __DIR__ . '/config/database.php';
$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "<h3>Database Connection</h3>";
    echo "Database connection successful<br>";
    
    // Check if the admin user exists
    $stmt = $conn->prepare("SELECT id, email, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "<h3>User Found in Database</h3>";
        echo "<pre>" . print_r($user, true) . "</pre>";
        
        // Check password hash
        echo "<h3>Password Verification</h3>";
        echo "Password from form: $password<br>";
        echo "Password hash in DB: " . $user['password'] . "<br>";
        echo "Verify password: " . (password_verify($password, $user['password']) ? 'true' : 'false') . "<br>";
    } else {
        echo "<h3>User Not Found in Database</h3>";
    }
} else {
    echo "<h3>Database Connection Failed</h3>";
}
?>