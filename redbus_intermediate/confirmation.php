<?php
require_once "config/database.php";
include "includes/auth.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST"
    || empty($_SESSION["pending_seats"])
    || empty($_SESSION["pending_bus_id"])) {
    die("No pending booking found. Please start again.");
}

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$phone = trim($_POST["phone"] ?? "");

if (!preg_match('/^[A-Za-z ]{3,50}$/', $name)
    || !filter_var($email, FILTER_VALIDATE_EMAIL)
    || !preg_match('/^[6-9][0-9]{9}$/', $phone)) {
    die("Passenger details are invalid. Please go back and try again.");
}

$busId = (int)$_SESSION["pending_bus_id"];
$date = $_SESSION["pending_date"];
$seats = $_SESSION["pending_seats"];

try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("SELECT fare, total_seats FROM buses WHERE bus_id = ?");
    $stmt->bind_param("i", $busId);
    $stmt->execute();
    $bus = $stmt->get_result()->fetch_assoc();
    if (!$bus) throw new Exception("Bus not found.");

    foreach ($seats as $seat) {
        $seatNumber = (int)substr($seat, 1);
        if ($seatNumber < 1 || $seatNumber > (int)$bus["total_seats"]) {
            throw new Exception("Seat number is outside the bus capacity.");
        }
    }

    $total = count($seats) * (int)$bus["fare"];
    $userId = (int)$_SESSION["user_id"];

    $stmt = $conn->prepare(
        "INSERT INTO bookings (user_id, bus_id, journey_date, passenger_name, email, phone, total_amount)
         VALUES (?, ?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param("iissssi", $userId, $busId, $date, $name, $email, $phone, $total);
    $stmt->execute();
    $bookingId = $conn->insert_id;

    $stmt = $conn->prepare(
        "INSERT INTO booking_seats (booking_id, bus_id, journey_date, seat_number)
         VALUES (?, ?, ?, ?)"
    );
    foreach ($seats as $seat) {
        $stmt->bind_param("iiss", $bookingId, $busId, $date, $seat);
        $stmt->execute(); // Unique key rejects a seat already booked for this bus/date.
    }

    $conn->commit();
    unset($_SESSION["pending_bus_id"], $_SESSION["pending_date"], $_SESSION["pending_seats"]);
    include "includes/header.php";
    ?>
    <section class="form-card">
        <h1>Demo booking confirmed!</h1>
        <p>Booking ID: <strong><?= $bookingId ?></strong></p>
        <p>Passenger: <?= htmlspecialchars($name) ?></p>
        <p>Journey date: <?= htmlspecialchars($date) ?></p>
        <p>Seats: <?= htmlspecialchars(implode(", ", $seats)) ?></p>
        <p>Total amount: ₹<?= number_format($total) ?></p>
        <p class="notice">SIMULATION ONLY – no payment was collected and no real ticket was issued.</p>
        <a class="button" href="bookings.php">View my bookings</a>
    </section>
    <?php
    include "includes/footer.php";
} catch (Throwable $error) {
    $conn->rollback();
    http_response_code(409);
    include "includes/header.php";
    echo "<section class='form-card'><h1>Booking not completed</h1>";
    echo "<p class='error'>One or more selected seats may already be booked. Return to seat selection and choose again.</p>";
    echo "<p>Technical message: " . htmlspecialchars($error->getMessage()) . "</p></section>";
    include "includes/footer.php";
}
?>