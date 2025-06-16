<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../classes/Fine.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth();
$fine = new Fine();
$action = $_GET['action'] ?? '';

if (!$auth->isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

switch ($action) {
    case 'list':
        $limit = 10;
        $page = max(1, $_GET['page'] ?? 1);
        $offset = ($page - 1) * $limit;
        $search = $_GET['search'] ?? '';
        
        if ($auth->hasRole(['admin', 'librarian'])) {
            $fines = $fine->getAllFines($limit, $offset, $search);
        } else {
            $fines = $fine->getUserFines($auth->getCurrentUser()['id']);
        }
        
        echo json_encode([
            'success' => true,
            'fines' => $fines,
            'total_pages' => ceil(count($fines) / $limit),
            'current_page' => $page
        ]);
        break;
        
    case 'pay':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $auth->hasRole(['admin', 'librarian'])) {
            $id = $_POST['id'] ?? 0;
            $paymentMethod = $_POST['payment_method'] ?? '';
            if ($fine->payFine($id, $paymentMethod)) {
                echo json_encode(['success' => true, 'message' => 'Fine paid successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to pay fine']);
            }
        }
        break;
        
    case 'waive':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $auth->hasRole(['admin', 'librarian'])) {
            $id = $_POST['id'] ?? 0;
            if ($fine->waiveFine($id)) {
                echo json_encode(['success' => true, 'message' => 'Fine waived successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to waive fine']);
            }
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>