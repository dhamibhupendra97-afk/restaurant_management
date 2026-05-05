<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$sql = "SELECT o.id, u.name AS user_name, u.email, f.name AS food_name, o.quantity, o.total_price, o.order_date
        FROM orders o
        INNER JOIN users u ON o.user_id = u.id
        INNER JOIN food f ON o.food_id = f.id
        ORDER BY o.id DESC";
$orders = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TasteTown | View Orders</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "includes/navbar.php"; ?>
<main class="container">
    <h1>All Orders</h1>
    <div class="table-wrap" style="margin-top:1rem;">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Food</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($orders && $orders->num_rows > 0): ?>
                <?php while ($row = $orders->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo (int)$row["id"]; ?></td>
                        <td><?php echo htmlspecialchars($row["user_name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["email"]); ?></td>
                        <td><?php echo htmlspecialchars($row["food_name"]); ?></td>
                        <td><?php echo (int)$row["quantity"]; ?></td>
                        <td>Rs <?php echo number_format((float)$row["total_price"], 2); ?></td>
                        <td><?php echo htmlspecialchars($row["order_date"]); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7">No orders available.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include "includes/footer.php"; ?>
<script src="js/script.js"></script>
</body>
</html>