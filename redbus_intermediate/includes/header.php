<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$lastVisit = $_COOKIE["last_visit"] ?? "This is your first visit";
setcookie("last_visit", date("d-m-Y H:i:s"), time() + 30 * 24 * 60 * 60, "/");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>redBus Educational Demo</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="navbar">
    <a class="logo" href="index.php">redBus</a>
    <nav>
        <a href="index.php">Home</a>
        <a href="bookings.php">My bookings</a>
        <a href="feedback.php">Feedback</a>
        <a href="interaction.php">Interaction demo</a>
        <?php if (isset($_SESSION["user_id"])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>
<p class="last-visit"><?= htmlspecialchars($lastVisit) ?></p>
<main>