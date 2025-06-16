<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - University Library</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <div id="loginScreen" class="login-container">
        <div class="login-card">
            <div class="login-header">
                <i class="fas fa-book-open fa-3x text-primary mb-3"></i>
                <h2>Library Management</h2>
                <p>University Central Library System</p>
            </div>
            
            <form id="loginForm" onsubmit="handleLogin(event)" method="post">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" required autocomplete="email">
                </div>
                
                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> Password</label>
                    <input type="password" class="form-control" id="password" name="password" required autocomplete="current-password">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block" id="loginButton">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
            
            <div class="mt-4 text-center">
                <small class="text-muted">
                    Demo Credentials:<br>
                    Admin: admin@university.edu / admin123<br>
                    Librarian: librarian@university.edu / lib123<br>
                    Student: alice@university.edu / student123
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script src="assets/js/app.js"></script>
</body>
</html>