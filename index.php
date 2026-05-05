<?php
session_start();
?>

<?php if (isset($_SESSION['user_name'])): ?>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> 👋</h2>
<?php else: ?>
    
<?php endif; ?>

<?php
require_once "config/db.php";
$foods = [];
$sql = "SELECT * FROM food ORDER BY id DESC LIMIT 8";
$result = $conn->query($sql);
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
    <title>TasteTown | Order Food Online</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "includes/navbar.php"; ?>

<main class="container">
    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Order food online from your favorite restaurants</h1>
            <p>TasteTown brings the best food from local restaurants directly to your table. Browse through delicious meals, quick checkout, and track your order in real-time!</p>
            <div class="gap-10" style="display: flex; gap: 12px; flex-wrap: wrap;">
                <a class="btn" href="menu.php">Browse Menu</a>
                <a class="btn btn-secondary" href="about.php">Learn More</a>
            </div>
        </div>
        <div class="hero-image">🍔</div>
    </section>

    <!-- Search Section -->
    <section class="search-section" style="margin: 60px 0;">
        <h2>Find Your Next Favorite Meal</h2>
        <form action="search.php" method="GET" class="search-bar">
            <input type="text" name="search" placeholder="Search for restaurants, cuisines, or dishes..." required>
            <button class="btn" type="submit">Search</button>
        </form>
    </section>

    <!-- Features Section -->
    <section style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin: 60px 0;">
        <div class="card" style="text-align: center;">
            <div style="font-size: 48px; padding: 20px; background: linear-gradient(135deg, #E23744, #ff6b6b); color: white; border-radius: 12px 12px 0 0;">🚀</div>
            <div class="card-body">
                <h3>Lightning Fast</h3>
                <p>Get your food delivered in 30 minutes or less. Lightning-fast service guaranteed.</p>
            </div>
        </div>
        <div class="card" style="text-align: center;">
            <div style="font-size: 48px; padding: 20px; background: linear-gradient(135deg, #4CAF50, #66BB6A); color: white; border-radius: 12px 12px 0 0;">✅</div>
            <div class="card-body">
                <h3>Quality Food</h3>
                <p>Only the freshest ingredients from trusted restaurants in your area.</p>
            </div>
        </div>
        <div class="card" style="text-align: center;">
            <div style="font-size: 48px; padding: 20px; background: linear-gradient(135deg, #2196F3, #42A5F5); color: white; border-radius: 12px 12px 0 0;">💰</div>
            <div class="card-body">
                <h3>Best Prices</h3>
                <p>Save money with exclusive deals, discounts, and offers every day.</p>
            </div>
        </div>
    </section>

    <!-- Popular Picks Section -->
    <section style="margin: 60px 0;">
        <h2 style="margin-bottom: 12px;">🔥 Popular Picks Right Now</h2>
        <p style="color: #696969; margin-bottom: 24px; font-size: 15px;">Most ordered dishes from restaurants near you</p>
        <div class="card-grid">
            <?php if (!empty($foods)): ?>
                <?php foreach ($foods as $food): ?>
                    <article class="card">
                        <div style="position: relative; overflow: hidden;">
                            <img src="<?php echo htmlspecialchars($food['image']); ?>" alt="<?php echo htmlspecialchars($food['name']); ?>" style="width: 100%; height: 180px; object-fit: cover;">
                            <div style="position: absolute; top: 8px; right: 8px; background: #E23744; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">NEW</div>
                        </div>
                        <div class="card-body">
                            <h3><?php echo htmlspecialchars($food['name']); ?></h3>
                            <div class="rating">
                                <span class="rating-stars">⭐⭐⭐⭐⭐</span>
                                <span class="rating-count">(152 ratings)</span>
                            </div>
                            <p style="color: #696969; font-size: 13px; margin-bottom: 8px;">
                                <?php echo ($food['food_type'] === 'veg') ? '🥬 Vegetarian' : '🍗 Non-Vegetarian'; ?>
                            </p>
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <span class="price">Rs <?php echo number_format((float)$food['price'], 2); ?></span>
                                <span style="background: #f0f0f0; padding: 2px 8px; border-radius: 4px; font-size: 12px; color: #696969;">25 mins</span>
                            </div>
                            <a class="btn" href="order.php?food_id=<?php echo (int)$food['id']; ?>" style="width: 100%; text-align: center;">Order Now</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="text-align: center; padding: 40px; color: #696969;">No food items available yet. Please check back soon!</p>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section style="background: linear-gradient(135deg, #E23744, #ff6b6b); padding: 60px 20px; border-radius: 12px; text-align: center; color: white; margin: 60px 0;">
        <h2 style="color: white; margin-bottom: 12px;">Ready to Order?</h2>
        <p style="color: rgba(255, 255, 255, 0.9); margin-bottom: 24px;">Sign in to access your favorite orders and get exclusive deals!</p>
        <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
            <?php if (!isset($_SESSION['user_id'])): ?>
                <a class="btn" href="register.php" style="background: white; color: #E23744;">Create Account</a>
                <a class="btn" style="background: rgba(255, 255, 255, 0.2); border: 2px solid white;" href="login.php">Login</a>
            <?php else: ?>
                <a class="btn" href="menu.php" style="background: white; color: #E23744;">Start Ordering</a>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include "includes/footer.php"; ?>
<script src="js/script.js"></script>
</body>
</html>