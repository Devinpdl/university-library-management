<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../classes/Setting.php';
require_once __DIR__ . '/../classes/Auth.php';

$auth = new Auth();
$setting = new Setting();
$action = $_GET['action'] ?? '';

if (!$auth->isLoggedIn() || !$auth->hasRole('admin')) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

switch ($action) {
    case 'list':
        $settings = $setting->getAllSettings();
        echo json_encode(['success' => true, 'settings' => $settings]);
        break;
        
    case 'update':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $key = $_POST['key'] ?? '';
            $value = $_POST['value'] ?? '';
            if ($setting->updateSetting($key, $value)) {
                echo json_encode(['success' => true, 'message' => 'Setting updated successfully']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update setting']);
            }
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>