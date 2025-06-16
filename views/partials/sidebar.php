<?php
require_once __DIR__ . '/../../classes/Auth.php';
$auth = new Auth();
$user = $auth->getCurrentUser();
?>

<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <h4><i class="fas fa-book-open mr-2"></i>Library System</h4>
        <small><?php echo htmlspecialchars($user['role']); ?></small>
    </div>
    <div class="sidebar-menu">
        <a href="#" class="sidebar-menu-item active" data-page="dashboard"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <?php if ($auth->hasRole(['admin', 'librarian'])): ?>
            <a href="#" class="sidebar-menu-item" data-page="books"><i class="fas fa-book"></i> Items</a>
            <a href="#" class="sidebar-menu-item" data-page="users"><i class="fas fa-users"></i> Users</a>
            <a href="#" class="sidebar-menu-item" data-page="issues"><i class="fas fa-exchange-alt"></i> Issues</a>
            <a href="#" class="sidebar-menu-item" data-page="reservations"><i class="fas fa-calendar-check"></i> Reservations</a>
            <a href="#" class="sidebar-menu-item" data-page="fines"><i class="fas fa-money-bill"></i> Fines</a>
            <a href="#" class="sidebar-menu-item" data-page="reports"><i class="fas fa-chart-bar"></i> Reports</a>
            <a href="#" class="sidebar-menu-item" data-page="settings"><i class="fas fa-cog"></i> Settings</a>
        <?php else: ?>
            <a href="#" class="sidebar-menu-item" data-page="books"><i class="fas fa-book"></i> Browse Items</a>
            <a href="#" class="sidebar-menu-item" data-page="issues"><i class="fas fa-exchange-alt"></i> My Issues</a>
            <a href="#" class="sidebar-menu-item" data-page="reservations"><i class="fas fa-calendar-check"></i> My Reservations</a>
            <a href="#" class="sidebar-menu-item" data-page="fines"><i class="fas fa-money-bill"></i> My Fines</a>
        <?php endif; ?>
    </div>
</div>