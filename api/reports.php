<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../classes/Report.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth();
$report = new Report();
$action = $_GET['action'] ?? '';

if (!$auth->isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

switch ($action) {
    case 'dashboard':
        if ($auth->hasRole(['admin', 'librarian'])) {
            $stats = $report->getDashboardStats();
            $activities = $report->getRecentActivities();
            echo json_encode([
                'success' => true,
                'total_books' => $stats['total_books'],
                'available_books' => $stats['available_books'],
                'issued_books' => $stats['issued_books'],
                'overdue_books' => $stats['overdue_books'],
                'active_reservations' => $stats['active_reservations'],
                'pending_fines' => $stats['pending_fines'],
                'recent_activities' => $activities
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        }
        break;
        
    case 'overdue':
        if ($auth->hasRole(['admin', 'librarian'])) {
            $limit = 10;
            $page = max(1, $_GET['page'] ?? 1);
            $offset = ($page - 1) * $limit;
            
            $overdueIssues = $report->getOverdueReport($limit, $offset);
            echo json_encode([
                'success' => true,
                'overdue_issues' => $overdueIssues,
                'total_pages' => ceil(count($overdueIssues) / $limit),
                'current_page' => $page
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>