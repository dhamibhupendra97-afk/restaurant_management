<?php
$success = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = htmlspecialchars($_POST["name"] ?? "");
    $success = "Thank you, $name. Your message has been received.";
}
include "includes/navbar.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - FoodHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <section class="container section form-wrap">
        <h1>Contact Us</h1>
        <?php if ($success): ?><p class="success-msg"><?php echo $success; ?></p><?php endif; ?>
        <form method="post" class="form-card">
            <label>Name</label>
            <input type="text" name="name" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Message</label>
            <textarea name="message" rows="5" required></textarea>
            <button type="submit" class="btn">Send</button>
        </form>
    </section>
    <?php include "includes/footer.php"; ?>
    <script src="js/script.js"></script>
</body>
</html>
