<?php
require_once "config/database.php";
include "includes/auth.php";
include "includes/header.php";
?>
<section class="form-card">
    <h1>Welcome, <?= htmlspecialchars($_SESSION["user_name"]) ?></h1>
    <p>You are logged in.</p>
    <p><a href="bookings.php">View booking history</a></p>
    <p><a href="index.php">Search for a bus</a></p>
    <p><a href="logout.php">Logout</a></p>
</section>
<?php include "includes/footer.php"; ?>