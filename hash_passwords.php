<?php
include 'config.php';

// Hash passwords for admin table
$sql = "SELECT id, password FROM admin";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
    if(!password_verify($row['password'], $row['password'])) { // Check if not already hashed
        $hashed = password_hash($row['password'], PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE admin SET password='$hashed' WHERE id={$row['id']}");
    }
}

// Hash passwords for librarian table
$sql = "SELECT id, password FROM librarian";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
    if(!password_verify($row['password'], $row['password'])) {
        $hashed = password_hash($row['password'], PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE librarian SET password='$hashed' WHERE id={$row['id']}");
    }
}

// Hash passwords for students table
$sql = "SELECT id, password FROM students";
$result = mysqli_query($conn, $sql);
while($row = mysqli_fetch_assoc($result)) {
    if(!password_verify($row['password'], $row['password'])) {
        $hashed = password_hash($row['password'], PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE students SET password='$hashed' WHERE id={$row['id']}");
    }
}

echo "Passwords have been hashed successfully!";
?>