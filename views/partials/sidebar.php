<?php
require_once __DIR__ . '/../../classes/Auth.php';
$auth = new Auth();
$user = $auth->getCurrentUser();
?>

<div id="sidebar" class="sidebar">
    <div class="sidebar-header">
        <h4><i class="fas fa-book-open me-2"></i>Library System</h4>
        <small class="d-block text-muted"><?php echo htmlspecialchars($user['role']); ?></small>
    </div>
    <div class="sidebar-menu">
        <a href="/university-library-management/?page=dashboard" class="sidebar-menu-item <?php echo (!isset($_GET['page']) || $_GET['page'] == 'dashboard') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <?php if ($auth->hasRole(['admin', 'librarian'])): ?>
            <a href="/university-library-management/?page=books" class="sidebar-menu-item <?php echo (isset($_GET['page']) && $_GET['page'] == 'books') ? 'active' : ''; ?>"><i class="fas fa-book"></i> Items</a>
            <a href="/university-library-management/?page=users" class="sidebar-menu-item <?php echo (isset($_GET['page']) && $_GET['page'] == 'users') ? 'active' : ''; ?>"><i class="fas fa-users"></i> Users</a>
            <a href="/university-library-management/?page=issues" class="sidebar-menu-item <?php echo (isset($_GET['page']) && $_GET['page'] == 'issues') ? 'active' : ''; ?>"><i class="fas fa-exchange-alt"></i> Issues</a>
            <a href="/university-library-management/?page=reservations" class="sidebar-menu-item <?php echo (isset($_GET['page']) && $_GET['page'] == 'reservations') ? 'active' : ''; ?>"><i class="fas fa-calendar-check"></i> Reservations</a>
            <a href="/university-library-management/?page=fines" class="sidebar-menu-item <?php echo (isset($_GET['page']) && $_GET['page'] == 'fines') ? 'active' : ''; ?>"><i class="fas fa-money-bill"></i> Fines</a>
            <a href="/university-library-management/?page=reports" class="sidebar-menu-item <?php echo (isset($_GET['page']) && $_GET['page'] == 'reports') ? 'active' : ''; ?>"><i class="fas fa-chart-bar"></i> Reports</a>
            <a href="/university-library-management/?page=settings" class="sidebar-menu-item <?php echo (isset($_GET['page']) && $_GET['page'] == 'settings') ? 'active' : ''; ?>"><i class="fas fa-cog"></i> Settings</a>
        <?php else: ?>
            <a href="/university-library-management/?page=books" class="sidebar-menu-item <?php echo (isset($_GET['page']) && $_GET['page'] == 'books') ? 'active' : ''; ?>"><i class="fas fa-book"></i> Browse Items</a>
            <a href="/university-library-management/?page=issues" class="sidebar-menu-item <?php echo (isset($_GET['page']) && $_GET['page'] == 'issues') ? 'active' : ''; ?>"><i class="fas fa-exchange-alt"></i> My Issues</a>
            <a href="/university-library-management/?page=reservations" class="sidebar-menu-item <?php echo (isset($_GET['page']) && $_GET['page'] == 'reservations') ? 'active' : ''; ?>"><i class="fas fa-calendar-check"></i> My Reservations</a>
            <a href="/university-library-management/?page=fines" class="sidebar-menu-item <?php echo (isset($_GET['page']) && $_GET['page'] == 'fines') ? 'active' : ''; ?>"><i class="fas fa-money-bill"></i> My Fines</a>
        <?php endif; ?>
    </div>
</div>