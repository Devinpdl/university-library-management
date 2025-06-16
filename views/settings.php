<?php
require_once __DIR__ . '/../classes/Auth.php';
$auth = new Auth();
if (!$auth->hasRole('admin')) {
    header('Location: index.php?page=dashboard');
    exit;
}
?>

<div id="settingsContent" class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>System Settings</h5>
            </div>
            <div class="card-body">
                <div id="settingsList">
                    <!-- Populated via JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>