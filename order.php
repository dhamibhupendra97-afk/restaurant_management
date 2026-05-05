<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$foodId = (int)($_GET["food_id"] ?? 0);
$foods = [];
$selectedFood = null;
$success = "";
$error = "";

$foodResult = $conn->query("SELECT id, name, price FROM food ORDER BY name ASC");
if ($foodResult) {
    while ($row = $foodResult->fetch_assoc()) {
        $foods[] = $row;
        if ($foodId > 0 && (int)$row["id"] === $foodId) {
            $selectedFood = $row;
        }
    }
}

if ($selectedFood === null && !empty($foods)) {
    $selectedFood = $foods[0];
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = (int)$_SESSION["user_id"];
    $foodIdPost = (int)($_POST["food_id"] ?? 0);
    $quantity = (int)($_POST["quantity"] ?? 1);

    if ($foodIdPost <= 0 || $quantity <= 0) {
        $error = "Please select a valid item and quantity.";
    } else {
        $foodStmt = $conn->prepare("SELECT price FROM food WHERE id = ?");
        $foodStmt->bind_param("i", $foodIdPost);
        $foodStmt->execute();
        $foodData = $foodStmt->get_result()->fetch_assoc();

        if (!$foodData) {
            $error = "Food item not found.";
        } else {
            $unitPrice = (float)$foodData["price"];
            $totalPrice = $unitPrice * $quantity;
            $insertStmt = $conn->prepare("INSERT INTO orders (user_id, food_id, quantity, total_price) VALUES (?, ?, ?, ?)");
            $insertStmt->bind_param("iiid", $userId, $foodIdPost, $quantity, $totalPrice);

            if ($insertStmt->execute()) {
                $success = "Order placed successfully.";
                $foodId = $foodIdPost;
                foreach ($foods as $food) {
                    if ((int)$food["id"] === $foodId) {
                        $selectedFood = $food;
                        break;
                    }
                }
            } else {
                $error = "Unable to place order.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TasteTown | Place Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "includes/navbar.php"; ?>
<main class="container">
    <section class="form-card">
        <h1>Place Order</h1>
        <?php if ($success): ?><p class="message success"><?php echo $success; ?></p><?php endif; ?>
        <?php if ($error): ?><p class="message error"><?php echo $error; ?></p><?php endif; ?>

        <?php if (!empty($foods)): ?>
            <form method="POST" action="">
                <div>
                    <label for="food_id">Select Food</label>
                    <select name="food_id" id="food_id" onchange="window.location='order.php?food_id=' + this.value;">
                        <?php foreach ($foods as $food): ?>
                            <option value="<?php echo (int)$food['id']; ?>" <?php echo ((int)$selectedFood['id'] === (int)$food['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($food['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="food_price">Price (Rs)</label>
                    <input type="number" id="food_price" value="<?php echo number_format((float)$selectedFood['price'], 2, '.', ''); ?>" readonly>
                </div>
                <div>
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="1" min="1" required>
                </div>
                <div>
                    <label for="total_price">Total (Rs)</label>
                    <input type="number" id="total_price" readonly>
                </div>
                <button class="btn" type="submit">Confirm Order</button>
            </form>
        <?php else: ?>
            <p>No food items available right now.</p>
        <?php endif; ?>
    </section>
</main>
<?php include "includes/footer.php"; ?>
<script src="js/script.js"></script>
</body>
</html>