<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../classes/Book.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth();
$book = new Book();
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
        $category = $_GET['category'] ?? '';
        $author = $_GET['author'] ?? '';
        $type = $_GET['type'] ?? '';
        
        $books = $book->getAllBooks($limit, $offset, $search, $category, $author, $type);
        $total = $book->getTotalCount($search, $category, $author, $type);
        $totalPages = (int)ceil($total / $limit);
        
        echo json_encode([
            'success' => true,
            'books' => $books,
            'total_pages' => $totalPages,
            'current_page' => $page
        ]);
        break;
        
    case 'get':
        $id = $_GET['id'] ?? 0;
        $bookData = $book->getBookById($id);
        if ($bookData) {
            echo json_encode(['success' => true, 'book' => $bookData]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Book not found']);
        }
        break;
        
    case 'add':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $auth->hasRole(['admin', 'librarian'])) {
            $data = $_POST;
            if (!empty($_FILES['cover_image']['name'])) {
                $targetDir = "../assets/images/";
                $targetFile = $targetDir . basename($_FILES['cover_image']['name']);
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetFile)) {
                    $data['cover_image'] = basename($_FILES['cover_image']['name']);
                }
            }
            
            if ($book->addBook($data)) {
                echo json_encode(['success' => true, 'message' => 'Book added successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to add book']);
            }
        }
        break;
        
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $auth->hasRole(['admin', 'librarian'])) {
            $id = $_POST['id'] ?? 0;
            $data = $_POST;
            if (!empty($_FILES['cover_image']['name'])) {
                $targetDir = "../assets/images/";
                $targetFile = $targetDir . basename($_FILES['cover_image']['name']);
                if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $targetFile)) {
                    $data['cover_image'] = basename($_FILES['cover_image']['name']);
                }
            }
            
            if ($book->updateBook($id, $data)) {
                echo json_encode(['success' => true, 'message' => 'Book updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update book']);
            }
        }
        break;
        
    case 'delete':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $auth->hasRole(['admin', 'librarian'])) {
            $id = $_POST['id'] ?? 0;
            if ($book->deleteBook($id)) {
                echo json_encode(['success' => true, 'message' => 'Book deleted successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to delete book']);
            }
        }
        break;
        
    case 'filters':
        echo json_encode([
            'success' => true,
            'categories' => $book->getCategories(),
            'authors' => $book->getAuthors(),
            'publishers' => $book->getPublishers()
        ]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>