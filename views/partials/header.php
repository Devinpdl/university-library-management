<?php
require_once __DIR__ . '/../../classes/Auth.php';
$auth = new Auth();
$user = $auth->getCurrentUser();
if (!$auth->isLoggedIn()) {
    header('Location: /login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>

<div class="top-header">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6">
                <button id="sidebarToggle" class="btn btn-link text-white d-md-none">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 id="pageTitle" class="d-inline-block mb-0 text-white">Dashboard</h4>
            </div>
            <div class="col-md-6 text-right">
                <span id="userInfo" class="text-white mr-2"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span>
                <div class="dropdown d-inline-block">
                    <button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown">
                        <i class="fas fa-user"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item" href="#" data-page="profile"><i class="fas fa-id-card"></i> Profile</a>
                        <a class="dropdown-item" href="#" id="themeToggle"><i class="fas fa-adjust"></i> Toggle Theme</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/navigation.js"></script>
<script src="/assets/js/app.js"></script>
</body>
</html>