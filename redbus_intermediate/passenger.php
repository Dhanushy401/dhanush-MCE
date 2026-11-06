<?php
require_once "config/database.php";
include "includes/auth.php";
include "includes/header.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "<p class='error'>Please select seats first.</p>";
} else {
    $busId = (int)($_POST["bus_id"] ?? 0);
    $date = $_POST["journey_date"] ?? "";
    $seatText = $_POST["selected_seats"] ?? "";
    $seats = array_filter(explode(",", $seatText));

    if ($busId < 1 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)
        || $date < date("Y-m-d") || !$seats || count($seats) !== count(array_unique($seats))) {
        echo "<p class='error'>Invalid seat selection. Go back and select seats again.</p>";
    } else {
        foreach ($seats as $seat) {
            if (!preg_match('/^S([1-9]|[1-3][0-9]|40)$/', $seat)) {
                die("<p class='error'>Invalid seat number.</p>");
            }
        }
        $_SESSION["pending_bus_id"] = $busId;
        $_SESSION["pending_date"] = $date;
        $_SESSION["pending_seats"] = array_values($seats);
        ?>
        <section class="form-card">
            <h1>Passenger details</h1>
            <p>Selected seats: <?= htmlspecialchars(implode(", ", $seats)) ?></p>
            <form action="confirmation.php" method="POST" onsubmit="return validatePassenger();">
                <label>Passenger name</label>
                <input name="name" id="passengerName" required>
                <small class="error" id="nameError"></small>
                <label>Email</label>
                <input type="email" name="email" id="passengerEmail" required>
                <small class="error" id="emailError"></small>
                <label>Phone number</label>
                <input name="phone" id="passengerPhone" maxlength="10" required>
                <small class="error" id="phoneError"></small>
                <button>Confirm simulated booking</button>
            </form>
        </section>
        <?php
    }
}
include "includes/footer.php";
?>