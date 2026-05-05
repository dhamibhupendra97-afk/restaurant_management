<?php
// Database configuration for XAMPP (localhost)
$host = "localhost";
$username = "root";
$password = "";
$database = "restaurant_db";

// Connect to MySQL server first (without selecting DB)
$conn = new mysqli($host, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it does not exist
$createDbSql = "CREATE DATABASE IF NOT EXISTS $database";
if (!$conn->query($createDbSql)) {
    die("Database creation failed: " . $conn->error);
}

// Select database
if (!$conn->select_db($database)) {
    die("Database selection failed: " . $conn->error);
}

// Create users table
$usersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($usersTable);

// Create food table
$foodTable = "CREATE TABLE IF NOT EXISTS food (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($foodTable);

// Create orders table
$ordersTable = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    food_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    total_price DECIMAL(10,2) NOT NULL,
    order_status VARCHAR(50) DEFAULT 'Placed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (food_id) REFERENCES food(id) ON DELETE CASCADE
)";
$conn->query($ordersTable);

// Insert default admin user if not present
$adminEmail = "admin@restaurant.com";
$adminPassword = password_hash("admin123", PASSWORD_DEFAULT);
$checkAdmin = $conn->query("SELECT id FROM users WHERE email = '$adminEmail' LIMIT 1");
if ($checkAdmin && $checkAdmin->num_rows === 0) {
    $conn->query("INSERT INTO users (name, email, password, role) VALUES ('Admin', '$adminEmail', '$adminPassword', 'admin')");
}
?>
