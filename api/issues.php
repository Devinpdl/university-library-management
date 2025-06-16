<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../classes/Issue.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth();
$issue = new Issue();
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
            $issues = $issue->getAllIssues($limit, $offset, $search);
        } else {
            $issues = $issue->getUserIssues($auth->getCurrentUser()['id']);
        }
        
        echo json_encode([
            'success' => true,
            'issues' => $issues,
            'total_pages' => ceil(count($issues) / $limit),
            'current_page' => $page
        ]);
        break;
        
    case 'issue':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $auth->hasRole(['admin', 'librarian'])) {
            $bookId = $_POST['book_id'] ?? 0;
            $userId = $_POST['user_id'] ?? 0;
            $dueDays = $_POST['due_days'] ?? 14;
            
            echo json_encode($issue->issueBook($bookId, $userId, $auth->getCurrentUser()['id'], $dueDays));
        }
        break;
        
    case 'return':
            if ($_POST['return_date'] && $auth->hasRole(['admin', 'librarian'])) {
                $issueId = $_POST['issue_id'] ?? 0;
                return $issue->returnBook($issueId, $auth->getCurrentUser()['id']);
            }
        break;
        
    default:
            return ['success' => false, 'message', 'Invalid action'];
        }
?>