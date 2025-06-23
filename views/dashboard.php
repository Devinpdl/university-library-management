<?php
require_once __DIR__ . '/../classes/Auth.php';
require_once __DIR__ . '/../classes/Report.php';

$auth = new Auth();

// Redirect to login if not authenticated
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit();
}
$report = new Report();
$stats = $report->getDashboardStats();

// Include header and sidebar
include_once __DIR__ . '/partials/header.php';
include_once __DIR__ . '/partials/sidebar.php';
?>

<div class="main-content">
    <div id="dashboardContent" class="page-content">
    <div class="container-fluid">
        <style>
            .stats-card {
                border: none;
                border-radius: 15px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                transition: transform 0.3s ease;
            }
            .stats-card:hover {
                transform: translateY(-5px);
            }
            .stats-card .card-body {
                padding: 1.5rem;
            }
            .stats-card .fa-2x {
                font-size: 2.5em;
                margin-right: 1rem;
            }
            .stats-card .card-title {
                color: #6c757d;
                font-size: 0.9rem;
                font-weight: 600;
                margin-bottom: 0.5rem;
                text-transform: uppercase;
            }
            .stats-card h3 {
                color: #2c3e50;
                font-size: 1.8rem;
                font-weight: 700;
                margin: 0;
            }
            .text-primary { color: #4e73df !important; }
            .text-success { color: #1cc88a !important; }
            .text-warning { color: #f6c23e !important; }
            .text-info { color: #36b9cc !important; }
            #itemsChart, #issuesChart {
                min-height: 400px;
                margin: 2rem 0;
                background: white;
                border-radius: 15px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
                padding: 1rem;
            }
        </style>
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

        <div class="col-md-6 mb-4">
            <div id="itemsChart"></div>
        </div>
        <div class="col-md-6 mb-4">
            <div id="issuesChart"></div>
        </div>
    </div>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Items Statistics Chart
                Highcharts.chart('itemsChart', {
                    chart: { type: 'pie' },
                    title: { text: 'Items Distribution' },
                    series: [{
                        name: 'Items',
                        data: [
                            ['Available', <?php echo $stats['available_books']; ?>],
                            ['Issued', <?php echo $stats['issued_books']; ?>]
                        ]
                    }]
                });

                // Monthly Issues Chart
                fetch('/api/reports.php?action=monthly_issues')
                    .then(response => response.json())
                    .then(data => {
                        Highcharts.chart('issuesChart', {
                            chart: { type: 'column' },
                            title: { text: 'Monthly Issues' },
                            xAxis: { categories: data.months },
                            yAxis: { title: { text: 'Number of Issues' } },
                            series: [{ name: 'Issues', data: data.counts }]
                        });
                    });
            });
        </script>
        
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Items Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div id="itemsChart" style="min-height: 300px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Monthly Issues</h5>
                    </div>
                    <div class="card-body">
                        <div id="issuesChart" style="min-height: 300px;"></div>
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
                                        <th>Times Issued</th>
                                        <th>Available</th>
                                    </tr>
                                </thead>
                                <tbody id="popularItemsTable">
                                    <?php foreach ($stats['popular_books'] as $book): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                                        <td><?php echo htmlspecialchars($book['times_issued']); ?></td>
                                        <td><?php echo $book['available'] ? 'Yes' : 'No'; ?></td>
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