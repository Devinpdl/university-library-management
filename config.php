<?php
$host = "localhost";
$dbname = "library_db";
$username = "root";
$password = "";

try {
    $conn = new mysqli($host, $username, $password, $dbname);
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die("Connection failed: " . $e->getMessage());
}

function debug_log($message) {
    error_log(date('Y-m-d H:i:s') . ": " . $message . "\n", 3, "debug.log");
}
?>