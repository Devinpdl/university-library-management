<?php
session_start();
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth();
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $result = $auth->login($username, $password);
            
            if ($result['success']) {
                header('Location: /university-library-management/');
                exit();
            } else {
                $_SESSION['login_error'] = $result['message'];
                header('Location: /university-library-management/');
                exit();
            }
        }
        break;

    case 'logout':
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header('Location: /university-library-management/login');
        exit();
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
        break;
}
?>