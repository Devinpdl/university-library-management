<?php
require_once __DIR__ . '/../classes/Auth.php';
$auth = new Auth();
?>

<div id="finesContent" class="page-content">
    <style>
        .page-content .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background: linear-gradient(to right, #4e73df, #36b9cc);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.2rem 1.5rem;
        }
        .card-header h5 {
            margin: 0;
            font-weight: 600;
        }
        .form-control {
            border-radius: 8px;
            border: 1px solid #e0e3e7;
            padding: 0.6rem 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        .btn-primary {
            background: #4e73df;
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #2e50bc;
        }
        .table {
            margin: 1rem 0;
        }
        .table thead th {
            border-top: none;
            border-bottom: 2px solid #e0e3e7;
            color: #6c757d;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        .table td {
            vertical-align: middle;
            padding: 1rem 0.75rem;
            color: #2c3e50;
        }
        .pagination {
            margin-top: 2rem;
        }
        .pagination .page-link {
            border: none;
            color: #4e73df;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            margin: 0 0.2rem;
            transition: all 0.3s ease;
        }
        .pagination .page-link:hover {
            background: #e8eaed;
        }
        .pagination .page-item.active .page-link {
            background: #4e73df;
            color: white;
        }
        .badge {
            padding: 0.5rem 0.8rem;
            border-radius: 6px;
            font-weight: 500;
        }
        .badge-success {
            background: #1cc88a;
        }
        .badge-warning {
            background: #f6c23e;
        }
        .badge-danger {
            background: #e74a3b;
        }
    </style>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5>Fines Management</h5>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <input type="text" id="fineSearch" class="form-control" placeholder="Search by user ID or title">
                    </div>
                    <div class="col-md-2">
                        <button id="fineSearchBtn" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
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