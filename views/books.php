<?php
require_once __DIR__ . '/../classes/Auth.php';
$auth = new Auth();
?>

<div id="booksContent" class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Items Management</h5>
                <?php if ($auth->hasRole(['admin', 'librarian'])): ?>
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addBookModal"><i class="fas fa-plus"></i> Add Item</button>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3">
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
                    <table class="table table-striped">
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