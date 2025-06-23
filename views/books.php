<?php
require_once __DIR__ . '/../classes/Auth.php';
$auth = new Auth();

// Redirect to login if not authenticated
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit();
}
?>

<div id="booksContent" class="page-content">
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
        .btn-primary {
            background: #fff;
            color: #4e73df;
            border: none;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #e8eaed;
            color: #2e50bc;
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
        .btn-secondary {
            background: #4e73df;
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-weight: 500;
        }
        .btn-outline-secondary {
            border: 1px solid #4e73df;
            color: #4e73df;
            border-radius: 8px;
            padding: 0.6rem 1rem;
            font-weight: 500;
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
        .pagination-container {
            margin-top: 2rem;
        }
        .modal-content {
            border: none;
            border-radius: 15px;
        }
        .modal-header {
            background: linear-gradient(to right, #4e73df, #36b9cc);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1.2rem 1.5rem;
        }
        .modal-title {
            font-weight: 600;
        }
    </style>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Items Management</h5>
                <?php if ($auth->hasRole(['admin', 'librarian'])): ?>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addBookModal"><i class="fas fa-plus"></i> Add Item</button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-3 mb-2 mb-md-0">
                        <input type="text" id="bookSearch" class="form-control" placeholder="Search by title, ISBN, or author">
                    </div>
                    <div class="col-md-2">
                        <select id="categoryFilter" class="form-control">
                            <option value="">All Categories</option>
                            <!-- Populated via JavaScript -->
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="authorFilter" class="form-control">
                            <option value="">All Authors</option>
                            <!-- Populated via JavaScript -->
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select id="typeFilter" class="form-control">
                            <option value="">All Types</option>
                            <option value="book">Books</option>
                            <option value="thesis">Theses</option>
                            <option value="manual">Manuals</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button id="applyFilters" class="btn btn-secondary"><i class="fas fa-filter"></i> Apply Filters</button>
                        <button id="resetFilters" class="btn btn-outline-secondary"><i class="fas fa-undo"></i> Reset</button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>ISBN</th>
                                <th>Author</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Available</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="booksTableBody">
                            <!-- Populated via JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <div id="booksPagination" class="pagination-container">
                    <!-- Populated via JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Book Modal -->
<div class="modal fade" id="addBookModal" tabindex="-1" role="dialog" aria-labelledby="addBookModalLabel" aria-hidden="true">
    <!-- Modal content populated via JavaScript -->
</div>

<!-- View Book Modal -->
<div class="modal fade" id="viewBookModal" tabindex="-1" role="dialog" aria-labelledby="viewBookModalLabel" aria-hidden="true">
    <!-- Modal content populated via JavaScript -->
</div>