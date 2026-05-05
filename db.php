<?php
// Database connection for XAMPP (MySQL)
$host = "localhost";
$username = "root";
$password = "";
$database = "restaurant_db";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Keep UTF-8 support enabled for text fields
$conn->set_charset("utf8mb4");
?>