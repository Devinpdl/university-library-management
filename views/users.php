<?php
require_once __DIR__ . '/../classes/Auth.php';
$auth = new Auth();
?>

<div id="usersContent" class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Users Management</h5>
                <?php if ($auth->hasRole('admin')): ?>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addUserModal"><i class="fas fa-plus"></i> Add User</button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="userSearch" class="form-control" placeholder="Search by name, email, or ID">
                    </div>
                    <div class="col-md-2">
                        <button id="userSearchBtn" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                            <!-- Populated via JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <nav>
                    <ul id="usersPagination" class="pagination justify-content-center">
                        <!-- Populated via JavaScript -->
                    </ul>
                </nav>
            </div>
        </div>