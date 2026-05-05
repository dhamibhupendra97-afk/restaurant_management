<?php
session_start();
require_once "config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: login.php");
    exit;
}

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $description = trim($_POST["description"] ?? "");
    $foodType = $_POST["food_type"] ?? "veg";
    $price = (float)($_POST["price"] ?? 0);
    $imagePath = "";

    if ($name === "" || $description === "" || $price <= 0) {
        $error = "All fields are required with valid values.";
    } elseif (!in_array($foodType, ["veg", "non_veg"], true)) {
        $error = "Please select valid food type.";
    } elseif (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== 0) {
        $error = "Please upload a valid image.";
    } else {
        $allowedTypes = ["image/jpeg", "image/png", "image/webp"];
        if (!in_array($_FILES["image"]["type"], $allowedTypes, true)) {
            $error = "Only JPG, PNG, and WEBP images are allowed.";
        } else {
            $uploadDir = "images/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
            $newFileName = "food_" . time() . "_" . rand(1000, 9999) . "." . $extension;
            $targetFile = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                $imagePath = $targetFile;
                $stmt = $conn->prepare("INSERT INTO food (name, description, food_type, price, image) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssds", $name, $description, $foodType, $price, $imagePath);
                if ($stmt->execute()) {
                    $success = "Food item added successfully.";
                } else {
                    $error = "Database insert failed.";
                }
            } else {
                $error = "Image upload failed.";
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
    <title>TasteTown | Add Food</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "includes/navbar.php"; ?>
<main class="container">
    <section class="form-card">
        <h1>Add Food Item</h1>
        <?php if ($success): ?><p class="message success"><?php echo $success; ?></p><?php endif; ?>
        <?php if ($error): ?><p class="message error"><?php echo $error; ?></p><?php endif; ?>
        <form method="POST" enctype="multipart/form-data" action="">
            <div>
                <label for="name">Food Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>
            <div>
                <label for="food_type">Food Type</label>
                <select id="food_type" name="food_type" required>
                    <option value="veg">Veg</option>
                    <option value="non_veg">Non-Veg</option>
                </select>
            </div>
            <div>
                <label for="price">Price (Rs)</label>
                <input type="number" step="0.01" id="price" name="price" required>
            </div>
            <div>
                <label for="image">Food Image</label>
                <input type="file" id="image" name="image" accept="image/*" required>
            </div>
            <button class="btn" type="submit">Add Food</button>
        </form>
    </section>
</main>
<?php include "includes/footer.php"; ?>
<script src="js/script.js"></script>
</body>
</html>