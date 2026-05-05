<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$userId = (int)$_SESSION["user_id"];
$stmt = $conn->prepare("SELECT o.id, f.name, o.quantity, o.total_price, o.order_date
                        FROM orders o
                        INNER JOIN food f ON o.food_id = f.id
                        WHERE o.user_id = ?
                        ORDER BY o.id DESC");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TasteTown | Order History</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "includes/navbar.php"; ?>
<main class="container">
    <h1>Your Order History</h1>
    <div class="table-wrap" style="margin-top:1rem;">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Food Item</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo (int)$row["id"]; ?></td>
                        <td><?php echo htmlspecialchars($row["name"]); ?></td>
                        <td><?php echo (int)$row["quantity"]; ?></td>
                        <td>Rs <?php echo number_format((float)$row["total_price"], 2); ?></td>
                        <td><?php echo htmlspecialchars($row["order_date"]); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No orders found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include "includes/footer.php"; ?>
<script src="js/script.js"></script>
</body>
</html>