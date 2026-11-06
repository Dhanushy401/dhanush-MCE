<?php
require_once "config/database.php";
include "includes/header.php";
?>
<section class="hero">
    <h1>Book Bus Tickets Easily</h1>
    <p>Search routes, choose seats and confirm your demo ticket.</p>
    <form action="buses.php" method="GET" class="search-box">
        <input name="source" placeholder="From city" required>
        <input name="destination" placeholder="To city" required>
        <input type="date" name="date" min="<?= date('Y-m-d') ?>" value="<?= date('Y-m-d') ?>" required>
        <button>Search Buses</button>
    </form>
</section>
<section class="features">
    <h2>Why use this demo?</h2>
    <div class="cards">
        <article class="card"><h3>Easy search</h3><p>Find buses by source and destination.</p></article>
        <article class="card"><h3>Choose seats</h3><p>Select available seats and see the total.</p></article>
        <article class="card"><h3>Demo ticket</h3><p>Complete a simulated booking without payment.</p></article>
    </div>
</section>
<?php include "includes/footer.php"; ?>