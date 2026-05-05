<?php
session_start();
require_once "config/db.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = "user";

    if ($name === "" || $email === "" || $password === "") {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email.";
    } else {
        $checkStmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertStmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $insertStmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

            if ($insertStmt->execute()) {
                $success = "Registration successful. You can now login.";
            } else {
                $error = "Something went wrong. Please try again.";
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
    <title>TasteTown | Register</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "includes/navbar.php"; ?>
<main class="container">
    <section class="form-card">
        <h1>Create Account</h1>
        <?php if ($success): ?><p class="message success"><?php echo $success; ?></p><?php endif; ?>
        <?php if ($error): ?><p class="message error"><?php echo $error; ?></p><?php endif; ?>
        <form method="POST" action="">
            <div>
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button class="btn" type="submit">Register</button>
        </form>
    </section>
</main>
<?php include "includes/footer.php"; ?>
<script src="js/script.js"></script>
</body>
</html>