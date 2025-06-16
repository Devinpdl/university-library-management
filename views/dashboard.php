<?php
require_once __DIR__ . '/../classes/Report.php';
$report = new Report();
$stats = $report->getDashboardStats();
?>

<div id="dashboardContent" class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-book fa-2x mr-3 text-primary"></i>
                            <div>
                                <h5 class="card-title">Total Items</h5>
                                <h3 id="totalBooks"><?php echo $stats['total_books']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-book-open fa-2x mr-3 text-success"></i>
                            <div>
                                <h5 class="card-title">Available Items</h5>
                                <h3 id="availableBooks"><?php echo $stats['available_books']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exchange-alt fa-2x mr-3 text-warning"></i>
                            <div>
                                <h5 class="card-title">Issued Items</h5>
                                <h3 id="issuedBooks"><?php echo $stats['issued_books']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users fa-2x mr-3 text-info"></i>
                            <div>
                                <h5 class="card-title">Total Users</h5>
                                <h3 id="totalUsers"><?php echo $stats['total_users']; ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Recent Issues</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>User</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                    </tr>
                                </thead>
                                <tbody id="recentIssuesTable">
                                    <?php foreach ($stats['recent_issues'] as $issue): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($issue['title']); ?></td>
                                        <td><?php echo htmlspecialchars($issue['user_name']); ?></td>
                                        <td><?php echo htmlspecialchars($issue['issue_date']); ?></td>
                                        <td><?php echo htmlspecialchars($issue['due_date']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Popular Items</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Author</th>
                                        <th>Category</th>
                                        <th>Issues</th>
                                    </tr>
                                </thead>
                                <tbody id="popularBooksTable">
                                    <?php foreach ($stats['popular_books'] as $book): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                                        <td><?php echo htmlspecialchars($book['category']); ?></td>
                                        <td><?php echo htmlspecialchars($book['issue_count']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>