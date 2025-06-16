<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../classes/Reservation.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth();
$reservation = new Reservation();
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
            $reservations = $reservation->getAllReservations($limit, $offset, $search);
        } else {
            $reservations = $reservation->getUserReservations($auth->getCurrentUser()['id']);
        }
        
        echo json_encode([
            'success' => true,
            'reservations' => $reservations,
            'total_pages' => ceil(count($reservations) / $limit),
            'current_page' => $page
        ]);
        break;
        
    case 'reserve':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $bookId = $_POST['book_id'] ?? 0;
            $userId = $_POST['user_id'] ?? $auth->getCurrentUser()['id'];
            
            echo json_encode($reservation->reserveBook($bookId, $userId));
        }
        break;
        
    case 'cancel':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? 0;
            if ($reservation->cancelReservation($id)) {
                echo json_encode(['success' => true, 'message' => 'Reservation cancelled successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to cancel reservation']);
            }
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>