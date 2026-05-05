<?php
require_once "config/db.php";
$query = trim($_GET["q"] ?? "");
$results = [];

if ($query !== "") {
    $stmt = $conn->prepare("SELECT * FROM food WHERE name LIKE ? OR description LIKE ? ORDER BY id DESC");
    $term = "%" . $query . "%";
    $stmt->bind_param("ss", $term, $term);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TasteTown | Search</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "includes/navbar.php"; ?>
<main class="container">
    <section class="form-card">
        <h1>Search Food Items</h1>
        <form method="GET" action="">
            <div>
                <label for="q">Keyword</label>
                <input type="text" name="q" id="q" value="<?php echo htmlspecialchars($query); ?>" placeholder="Pizza, burger, spicy, etc.">
            </div>
            <button class="btn" type="submit">Find</button>
        </form>
    </section>

    <?php if ($query !== ""): ?>
        <section>
            <h2 style="margin-top:1rem;">Search Results</h2>
            <div class="card-grid">
                <?php if (!empty($results)): ?>
                    <?php foreach ($results as $food): ?>
                        <article class="card">
                            <img src="<?php echo htmlspecialchars($food["image"]); ?>" alt="<?php echo htmlspecialchars($food["name"]); ?>">
                            <div class="card-body">
                                <h3><?php echo htmlspecialchars($food["name"]); ?></h3>
                                <p><?php echo htmlspecialchars($food["description"]); ?></p>
                                <p class="price">Rs <?php echo number_format((float)$food["price"], 2); ?></p>
                                <a class="btn" href="order.php?food_id=<?php echo (int)$food["id"]; ?>">Order</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No matching items found.</p>
                <?php endif; ?>
            </div>
        </section>
    <?php endif; ?>
</main>
<?php include "includes/footer.php"; ?>
<script src="js/script.js"></script>
</body>
</html>