<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$userCount = (int)$conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()["total"];
$foodCount = (int)$conn->query("SELECT COUNT(*) AS total FROM food")->fetch_assoc()["total"];
$orderCount = (int)$conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()["total"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TasteTown | Admin Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "includes/navbar.php"; ?>
<main class="container">
    <h1>Admin Dashboard</h1>
    <div class="card-grid" style="margin-top:1rem;">
        <div class="card"><div class="card-body"><h3>Total Users</h3><p class="price"><?php echo $userCount; ?></p></div></div>
        <div class="card"><div class="card-body"><h3>Total Foods</h3><p class="price"><?php echo $foodCount; ?></p></div></div>
        <div class="card"><div class="card-body"><h3>Total Orders</h3><p class="price"><?php echo $orderCount; ?></p></div></div>
    </div>
    <div style="margin-top:1rem; display:flex; gap:10px; flex-wrap:wrap;">
        <a class="btn" href="add_food.php">Add Food</a>
        <a class="btn btn-outline" href="delete_food.php">Delete Food</a>
        <a class="btn btn-outline" href="view_orders.php">View Orders</a>
    </div>
</main>
<?php include "includes/footer.php"; ?>
<script src="js/script.js"></script>
</body>
</html>