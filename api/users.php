<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../classes/User.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth();
$user = new User();
$action = $_GET['action'] ?? '';

if (!$auth->isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

switch ($action) {
    case 'list':
        if ($auth->hasRole('admin')) {
            $limit = 10;
            $page = max(1, $_GET['page'] ?? 1);
            $offset = ($page - 1) * $limit;
            $search = $_GET['search'] ?? '';
            
            $users = $user->getAllUsers($limit, $offset, $search);
            $total = $user->getTotalCount($search);
            $totalPages = ceil($total / $limit);
            
            echo json_encode([
                'success' => true,
                'users' => $users,
                'total_pages' => $totalPages,
                'current_page' => $page
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        }
        break;
        
    case 'get':
        if ($auth->hasRole('admin')) {
            $id = $_GET['id'] ?? 0;
            $userData = $user->getUserById($id);
            if ($userData) {
                echo json_encode(['success' => true, 'user' => $userData]);
            } else {
                echo json_encode(['success' => false, 'message' => 'User not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        }
        break;
        
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $auth->hasRole('admin')) {
            $data = $_POST;
            if ($user->addUser($data)) {
                echo json_encode(['success' => true, 'message' => 'User added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add user']);
            }
        }
        break;
        
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            $data = $_POST;
            if ($auth->hasRole('admin') || $id == $auth->getCurrentUser()['id']) {
                if ($user->updateUser($id, $data)) {
                    echo json_encode(['success' => true, 'message' => 'User updated successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to update user']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            }
        }
        break;
        
    case 'profile':
        $userData = $auth->getCurrentUser();
        if ($userData) {
            echo json_encode(['success' => true, 'first_name' => $userData['first_name'], 'last_name' => $userData['last_name'], 'email' => $userData['email'], 'phone' => $userData['phone'], 'address' => $userData['address']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>