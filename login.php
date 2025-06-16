<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    
    // Check user type
    $userType = $_POST['userType'];
    $table = "";
    
    switch($userType) {
        case 'admin':
            $table = "admin";
            break;
        case 'librarian':
            $table = "librarian";
            break;
        case 'student':
            $table = "students";
            break;
        default:
            echo "Invalid user type";
            exit();
    }
    
    $sql = "SELECT * FROM $table WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);
    
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        // Verify password
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['user_type'] = $userType;
            
            // Redirect based on user type
            switch($userType) {
                case 'admin':
                    header("Location: admin/dashboard.php");
                    break;
                case 'librarian':
                    header("Location: librarian/dashboard.php");
                    break;
                case 'student':
                    header("Location: student/dashboard.php");
                    break;
            }
            exit();
        } else {
            echo "<script>alert('Invalid password'); window.location.href='index.php';</script>";
        }
    } else {
        echo "<script>alert('User not found'); window.location.href='index.php';</script>";
    }
}
?>