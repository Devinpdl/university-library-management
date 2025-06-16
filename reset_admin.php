<?php
include 'config.php';

$email = 'admin@university.edu';
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "UPDATE admin SET password = ? WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $hashed_password, $email);

if ($stmt->execute()) {
    echo "Admin password reset successfully!";
} else {
    echo "Error resetting password: " . $conn->error;
}

$stmt->close();
$conn->close();
?>