<?php
require_once __DIR__ . '/../classes/Auth.php';
$auth = new Auth();
?>

<div id="reservationsContent" class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>Reservations Management</h5>
                <button class="btn btn-primary" data-toggle="modal" data-target="#reserveBookModal"><i class="fas fa-plus"></i> Reserve Item</button>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="reservationSearch" class="form-control" placeholder="Search by title, ISBN, or user ID">
                    </div>
                    <div class="col-md-2">
                        <button id="reservationSearchBtn" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>User</th>
                                <th>Reservation Date</th>
                                <th>Expiry Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reservationsTableBody">
                            <!-- Populated via JavaScript -->
                        </tbody>
                    </table>
                </div>
                
                <nav>
                    <ul id="reservationsPagination" class="pagination justify-content-center">
                        <!-- Populated via JavaScript -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>