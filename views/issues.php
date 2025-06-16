<?php
require_once __DIR__ . '/../classes/Auth.php';
$auth = new Auth();
?>

<div id="issuesContent" class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Issues Management</h5>
                <?php if ($auth->hasRole(['admin', 'librarian'])): ?>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#issueBookModal"><i class="fas fa-plus"></i> Issue Item</button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="issueSearch" class="form-control" placeholder="Search by title, ISBN, or user ID">
                    </div>
                    <div class="col-md-2">
                        <button id="issueSearchBtn" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>User</th>
                                <th>Issue Date</th>
                                <th>Due Date</th>
                                <th>Status</th>
                                <?php if ($auth->hasRole(['admin', 'librarian'])): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="issuesTableBody">
                            <!-- Populated via JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <nav>
                    <ul id="issuesPagination" class="pagination justify-content-center">
                        <!-- Populated via JavaScript -->
                    </ul>
                </nav>