<?php
require_once __DIR__ . '/../../classes/Auth.php';
$auth = new Auth();
$user = $auth->getCurrentUser();
if (!$auth->isLoggedIn()) {
    header('Location: /university-library-management/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="/university-library-management/assets/css/style.css" rel="stylesheet">
    <style>
        body {
            padding-top: 60px;
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .dropdown-menu {
            margin-top: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        }
        @media (max-width: 768px) {
            .navbar-brand {
                margin-left: 3rem;
            }
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <button id="sidebarToggle" class="btn btn-link text-white d-md-none">
                <i class="fas fa-bars"></i>
            </button>
            <h4 id="pageTitle" class="navbar-brand mb-0">Dashboard</h4>
            
            <div class="ml-auto">
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-user"></i> <?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="?page=profile"><i class="fas fa-id-card"></i> Profile</a>
                        <a class="dropdown-item" href="#" id="themeToggle"><i class="fas fa-adjust"></i> Toggle Theme</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="wrapper d-flex">
        <?php include 'sidebar.php'; ?>
        <div class="main-content flex-grow-1">
            <div class="container-fluid py-3">
                <!-- Main content will be here -->