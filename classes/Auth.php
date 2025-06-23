<?php
require_once __DIR__ . '/../config/database.php';
session_start();

class Auth {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function login($username, $password) {
        try {
            // Validate input
            if (empty($username) || empty($password)) {
                return ['success' => false, 'message' => 'Username and password are required'];
            }
            
            // Use the unified users table - try to find user by username or email
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                return ['success' => false, 'message' => 'User not found'];
            }
            
            // Verify password using password_verify since passwords are hashed
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'] ?? $user['email'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['user_type'] = $user['role'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_role'] = $user['role']; // Add this for hasRole function
                
                // Log the successful login
                $this->logActivity($user['id'], 'login', 'User logged in successfully');
                
                return [
                    'success' => true,
                    'message' => 'Login successful'
                ];
            }
            
            return ['success' => false, 'message' => 'Invalid password'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Login error: ' . $e->getMessage()];
        }
    }
    
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->logActivity($_SESSION['user_id'], 'logout', 'User logged out');
        }
        // Clear all session variables
        $_SESSION = array();
        
        // Destroy the session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Destroy the session
        session_destroy();
        
        return ['success' => true];
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function hasRole($roles) {
        if (!$this->isLoggedIn()) return false;
        $userRole = $_SESSION['user_role'];
        return in_array($userRole, is_array($roles) ? $roles : [$roles]);
    }
    
    public function getCurrentUser() {
        if (!$this->isLoggedIn()) return null;
        
        try {
            $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, role, status FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // If there's an error (like missing column), try with a more basic query
            try {
                $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, role FROM users WHERE id = ?");
                $stmt->execute([$_SESSION['user_id']]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (Exception $e) {
                return null;
            }
        }
    }
    
    private function logActivity($userId, $action, $description) {
        $stmt = $this->db->prepare("INSERT INTO activity_logs (user_id, action, description) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $action, $description]);
    }
}
?>