<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

if (isset($_GET["delete_id"])) {
    $deleteId = (int)$_GET["delete_id"];

    $imgStmt = $conn->prepare("SELECT image FROM food WHERE id = ?");
    $imgStmt->bind_param("i", $deleteId);
    $imgStmt->execute();
    $imgRow = $imgStmt->get_result()->fetch_assoc();

    $delStmt = $conn->prepare("DELETE FROM food WHERE id = ?");
    $delStmt->bind_param("i", $deleteId);
    if ($delStmt->execute() && $imgRow && !empty($imgRow["image"]) && file_exists($imgRow["image"])) {
        unlink($imgRow["image"]);
    }

    header("Location: delete_food.php");
    exit;
}

$foods = $conn->query("SELECT * FROM food ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TasteTown | Delete Food</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "includes/navbar.php"; ?>
<main class="container">
    <h1>Delete Food Items</h1>
    <div class="table-wrap" style="margin-top:1rem;">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($foods && $foods->num_rows > 0): ?>
                <?php while ($food = $foods->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo (int)$food["id"]; ?></td>
                        <td><?php echo htmlspecialchars($food["name"]); ?></td>
                        <td>Rs <?php echo number_format((float)$food["price"], 2); ?></td>
                        <td>
                            <a class="btn" href="delete_food.php?delete_id=<?php echo (int)$food["id"]; ?>" onclick="return confirm('Delete this item?')">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">No food items found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<?php include "includes/footer.php"; ?>
<script src="js/script.js"></script>
</body>
</html>