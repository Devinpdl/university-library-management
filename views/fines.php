<?php
require_once __DIR__ . '/../classes/Auth.php';
$auth = new Auth();
?>

<div id="finesContent" class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>Fines Management</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="fineSearch" class="form-control" placeholder="Search by user ID or title">
                    </div>
                    <div class="col-md-2">
                        <button id="fineSearchBtn" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Title</th>
                                <th>Amount</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <?php if ($auth->hasRole(['admin', 'librarian'])): ?>
                                    <th>Actions</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody id="finesTableBody">
                            <!-- Populated via JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <nav>
                    <ul id="finesPagination" class="pagination justify-content-center">
                        <!-- Populated via JavaScript -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>