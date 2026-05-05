<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$isAdmin = $isLoggedIn && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
?>
<header class="site-header">
    <nav class="navbar container">
        <a class="brand" href="index.php">🍽️ TasteTown</a>
        <button id="menuToggle" class="menu-toggle" type="button">☰</button>
        <ul id="navLinks" class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="menu.php">Menu</a></li>
            <li><a href="search.php">Search</a></li>
            <li><a href="about.php">About</a></li>
            <li><a href="contact.php">Contact</a></li>
            <?php if ($isLoggedIn): ?>
                <li><a href="order.php">Order</a></li>
                <li><a href="history.php">My Orders</a></li>
                <?php if ($isAdmin): ?>
                    <li><a href="admin_dashboard.php" style="color: #E23744; font-weight: 600;">Admin</a></li>
                <?php endif; ?>
                <li><a href="logout.php" class="btn" style="padding: 8px 16px; display: inline-block;">Logout</a></li>
            <?php else: ?>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php" class="btn" style="padding: 8px 16px; display: inline-block;">Sign Up</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');
    
    menuToggle.addEventListener('click', function() {
        navLinks.classList.toggle('active');
    });
    
    // Close menu when a link is clicked
    navLinks.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', function() {
            navLinks.classList.remove('active');
        });
    });
    
    // Dark mode toggle
    const darkModeToggle = document.getElementById('darkModeToggle');
    const isDarkMode = localStorage.getItem('darkMode') === 'true';
    
    if (isDarkMode) {
        document.body.classList.add('dark-mode');
    }
    
    darkModeToggle.addEventListener('click', function() {
        document.body.classList.toggle('dark-mode');
        localStorage.setItem('darkMode', document.body.classList.contains('dark-mode'));
    });
});
</script>
