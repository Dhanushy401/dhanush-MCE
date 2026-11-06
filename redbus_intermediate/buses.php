<?php
require_once "config/database.php";
include "includes/header.php";

$source = trim($_GET["source"] ?? "");
$destination = trim($_GET["destination"] ?? "");
$date = $_GET["date"] ?? date("Y-m-d");
$sort = $_GET["sort"] ?? "low";

if ($source === "" || $destination === "") {
    echo "<p class='notice'>Enter both source and destination on the home page.</p>";
} elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)
    || !checkdate((int)substr($date, 5, 2), (int)substr($date, 8, 2), (int)substr($date, 0, 4))
    || $date < date("Y-m-d")) {
    echo "<p class='error'>Please choose a valid future journey date.</p>";
} else {
    $sql = "SELECT b.*, r.source, r.destination
            FROM buses b JOIN routes r ON b.route_id = r.route_id
            WHERE LOWER(r.source) = LOWER(?) AND LOWER(r.destination) = LOWER(?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $source, $destination);
    $stmt->execute();
    $result = $stmt->get_result();
    $buses = [];
    while ($row = $result->fetch_assoc()) {
        $buses[] = $row; // Associative array of bus records.
    }

    // Sort the array using the option selected by the user.
    usort($buses, function ($a, $b) use ($sort) {
        if ($sort === "high") return $b["fare"] <=> $a["fare"];
        if ($sort === "early") return strcmp($a["departure"], $b["departure"]);
        return $a["fare"] <=> $b["fare"];
    });
    ?>
    <section class="container">
        <h1><?= htmlspecialchars($source) ?> to <?= htmlspecialchars($destination) ?></h1>
        <p>Journey date: <?= htmlspecialchars($date) ?></p>
        <form method="GET" class="sort-form">
            <input type="hidden" name="source" value="<?= htmlspecialchars($source) ?>">
            <input type="hidden" name="destination" value="<?= htmlspecialchars($destination) ?>">
            <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
            <label>Sort:
                <select name="sort">
                    <option value="low" <?= $sort === "low" ? "selected" : "" ?>>Price: low to high</option>
                    <option value="high" <?= $sort === "high" ? "selected" : "" ?>>Price: high to low</option>
                    <option value="early" <?= $sort === "early" ? "selected" : "" ?>>Departure: earliest</option>
                </select>
            </label>
            <button>Apply</button>
        </form>
        <?php if (!$buses): ?>
            <p class="notice">No buses found for this route.</p>
        <?php endif; ?>
        <?php foreach ($buses as $bus): ?>
            <article class="bus-card">
                <div>
                    <h2><?= htmlspecialchars($bus["bus_name"]) ?></h2>
                    <p><?= htmlspecialchars($bus["bus_type"]) ?></p>
                    <p><?= htmlspecialchars($bus["departure"]) ?> – <?= htmlspecialchars($bus["arrival"]) ?></p>
                </div>
                <div>
                    <h2>₹<?= number_format($bus["fare"]) ?></h2>
                    <a class="button" href="seats.php?bus_id=<?= (int)$bus["bus_id"] ?>&date=<?= urlencode($date) ?>">Select seats</a>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
    <?php
}
include "includes/footer.php";
?>