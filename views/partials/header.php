<?php
require_once __DIR__ . '/../../classes/Auth.php';
$auth = new Auth();
$user = $auth->getCurrentUser();
?>

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