$_SESSION['user_name'] = $row['name']; // or username
<?php
session_start();
require_once "config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $error = "Please enter email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                $_SESSION["user_id"] = $user["id"];
                $_SESSION["name"] = $user["name"];
                $_SESSION["role"] = $user["role"];

                if ($user["role"] === "admin") {
                    header("Location: admin_dashboard.php");
                    exit;
                }

                header("Location: index.php");
                exit;
            }
        }
        $error = "Invalid login details.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TasteTown | Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include "includes/navbar.php"; ?>
<main class="container">
    <section class="form-card">
        <h1>Login</h1>
        <?php if ($error): ?><p class="message error"><?php echo $error; ?></p><?php endif; ?>
        <form method="POST" action="">
            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button class="btn" type="submit">Login</button>
        </form>
    </section>
</main>
<?php include "includes/footer.php"; ?>
<script src="js/script.js"></script>
</body>
</html>