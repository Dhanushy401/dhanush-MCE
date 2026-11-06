<?php
require_once "config/database.php";
include "includes/auth.php";
include "includes/header.php";

$busId = (int)($_GET["bus_id"] ?? 0);
$date = $_GET["date"] ?? "";
if ($busId < 1 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)
    || !checkdate((int)substr($date, 5, 2), (int)substr($date, 8, 2), (int)substr($date, 0, 4))
    || $date < date("Y-m-d")) {
    die("<p class='error'>Invalid bus or journey date.</p>");
}
$stmt = $conn->prepare("SELECT * FROM buses WHERE bus_id = ?");
$stmt->bind_param("i", $busId);
$stmt->execute();
$bus = $stmt->get_result()->fetch_assoc();
if (!$bus) die("<p class='error'>Bus not found.</p>");

$stmt = $conn->prepare("SELECT seat_number FROM booking_seats WHERE bus_id = ? AND journey_date = ?");
$stmt->bind_param("is", $busId, $date);
$stmt->execute();
$result = $stmt->get_result();
$booked = [];
while ($row = $result->fetch_assoc()) $booked[] = $row["seat_number"];
?>
<section class="form-card wide">
    <h1>Select seats</h1>
    <p><?= htmlspecialchars($bus["bus_name"]) ?> | Fare per seat: ₹<?= (int)$bus["fare"] ?></p>
    <p>Grey = booked, green = selected, white = available.</p>
    <form action="passenger.php" method="POST" id="seatForm">
        <input type="hidden" name="bus_id" value="<?= $busId ?>">
        <input type="hidden" name="journey_date" value="<?= htmlspecialchars($date) ?>">
        <input type="hidden" name="selected_seats" id="selectedSeats">
        <div class="seat-grid">
            <?php for ($i = 1; $i <= (int)$bus["total_seats"]; $i++):
                $seat = "S" . $i;
                $isBooked = in_array($seat, $booked, true);
            ?>
                <button type="button"
                    class="seat <?= $isBooked ? "booked" : "" ?>"
                    data-seat="<?= $seat ?>"
                    data-fare="<?= (int)$bus["fare"] ?>"
                    <?= $isBooked ? "disabled" : "" ?>>
                    <?= $seat ?>
                </button>
            <?php endfor; ?>
        </div>
        <p id="seatTotal">Total: ₹0</p>
        <button type="submit">Continue to passenger details</button>
    </form>
</section>
<?php include "includes/footer.php"; ?>