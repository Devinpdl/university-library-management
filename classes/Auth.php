<?php
require_once __DIR__ . '/../config/database.php';
session_start();

class Auth {
    private $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }
    
    public function login($email, $password) {
        try {
            // Validate input
            if (empty($email) || empty($password)) {
                return ['success' => false, 'message' => 'Email and password are required'];
            }
            
            // Prepare statement to prevent SQL injection
            $stmt = $this->db->prepare("SELECT id, first_name, last_name, email, password, role, status FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Check if user exists and is active
            if (!$user) {
                return ['success' => false, 'message' => 'User not found'];
            }
            
            // Only check status if the column exists
            if (isset($user['status']) && $user['status'] !== 'active') {
                return ['success' => false, 'message' => 'Account is inactive'];
            }
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['first_name'] . ' ' . $user['last_name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                
                // Log activity
                $this->logActivity($user['id'], 'login', 'User logged in successfully');
                
                // Remove password from user data before returning
                unset($user['password']);
                
                return ['success' => true, 'user' => $user];
            } else {
                return ['success' => false, 'message' => 'Invalid password'];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'Login error: ' . $e->getMessage()];
        }
    }
    
    public function logout() {
        if (isset($_SESSION['user_id'])) {
            $this->logActivity($_SESSION['user_id'], 'logout', 'User logged out');
        }
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