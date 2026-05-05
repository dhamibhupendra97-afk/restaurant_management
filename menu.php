<?php
require_once "config/db.php";

$search = trim($_GET["search"] ?? "");
$type = trim($_GET["type"] ?? "");
$foods = [];

if ($search !== "" && in_array($type, ["veg", "non_veg"], true)) {
    $stmt = $conn->prepare("SELECT * FROM food WHERE name LIKE ? AND food_type = ? ORDER BY id DESC");
    $like = "%" . $search . "%";
    $stmt->bind_param("ss", $like, $type);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif ($search !== "") {
    $stmt = $conn->prepare("SELECT * FROM food WHERE name LIKE ? ORDER BY id DESC");
    $like = "%" . $search . "%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif (in_array($type, ["veg", "non_veg"], true)) {
    $stmt = $conn->prepare("SELECT * FROM food WHERE food_type = ? ORDER BY id DESC");
    $stmt->bind_param("s", $type);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM food ORDER BY id DESC");
}

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $foods[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TasteTown | Browse Menu</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "includes/navbar.php"; ?>

<main class="container">
    <!-- Header Section -->
    <section style="margin-bottom: 40px;">
        <h1 style="font-size: 36px; margin-bottom: 12px;">Browse Our Menu</h1>
        <p style="color: #696969; font-size: 16px;">Explore a wide variety of delicious dishes from our restaurant</p>
    </section>

    <!-- Search & Filter Section -->
    <section class="form-card" style="margin-bottom: 40px;">
        <form method="GET" action="">
            <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 16px; align-items: end;">
                <div>
                    <label for="search">🔍 Search Dishes</label>
                    <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Search by dish name or ingredient...">
                </div>
                <div>
                    <label for="type">🌿 Food Type</label>
                    <select id="type" name="type">
                        <option value="">All Types</option>
                        <option value="veg" <?php echo ($type === "veg") ? "selected" : ""; ?>>🥬 Vegetarian</option>
                        <option value="non_veg" <?php echo ($type === "non_veg") ? "selected" : ""; ?>>🍗 Non-Vegetarian</option>
                    </select>
                </div>
                <button class="btn" type="submit" style="padding: 12px 32px;">Search</button>
            </div>
        </form>
    </section>

    <!-- Results Count -->
    <div style="margin-bottom: 24px;">
        <p style="color: #696969; font-size: 14px;">
            Found <strong><?php echo count($foods); ?></strong> delicious dish<?php echo count($foods) !== 1 ? 'es' : ''; ?>
            <?php if ($search || $type): ?>
                matching your criteria
            <?php endif; ?>
        </p>
    </div>

    <!-- Food Grid -->
    <section class="card-grid" style="margin-bottom: 40px;">
        <?php if (!empty($foods)): ?>
            <?php foreach ($foods as $food): ?>
                <article class="card">
                    <div style="position: relative; overflow: hidden;">
                        <img src="<?php echo htmlspecialchars($food["image"]); ?>" alt="<?php echo htmlspecialchars($food["name"]); ?>" style="width: 100%; height: 180px; object-fit: cover;">
                        <div style="position: absolute; top: 8px; right: 8px; background: <?php echo ($food["food_type"] === "veg") ? '#4CAF50' : '#E23744'; ?>; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                            <?php echo ($food["food_type"] === "veg") ? "🥬 VEG" : "🍗 NON-VEG"; ?>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3><?php echo htmlspecialchars($food["name"]); ?></h3>
                        <div class="rating" style="margin-bottom: 8px;">
                            <span class="rating-stars">⭐⭐⭐⭐⭐</span>
                            <span class="rating-count">(234 ratings)</span>
                        </div>
                        <p style="color: #696969; font-size: 13px; margin-bottom: 8px; height: 36px; overflow: hidden;">
                            <?php echo htmlspecialchars($food["description"] ?? "Delicious and freshly prepared"); ?>
                        </p>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                            <span class="price">Rs <?php echo number_format((float)$food["price"], 2); ?></span>
                            <span style="background: #f0f0f0; padding: 2px 8px; border-radius: 4px; font-size: 12px; color: #696969;">30 mins</span>
                        </div>
                        <a class="btn" href="order.php?food_id=<?php echo (int)$food["id"]; ?>" style="width: 100%; text-align: center;">Order Now</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
                <div style="font-size: 64px; margin-bottom: 16px;">🔍</div>
                <h3 style="margin-bottom: 8px;">No dishes found</h3>
                <p style="color: #696969; margin-bottom: 20px;">Try searching with different keywords or filters</p>
                <a class="btn" href="menu.php">View All Dishes</a>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include "includes/footer.php"; ?>
<script src="js/script.js"></script>
</body>
</html>