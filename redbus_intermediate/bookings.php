<?php
require_once "config/database.php";
include "includes/auth.php";
include "includes/header.php";
$userId = (int)$_SESSION["user_id"];
$stmt = $conn->prepare(
    "SELECT bk.booking_id, bk.journey_date, bk.passenger_name, bk.total_amount,
            b.bus_name, GROUP_CONCAT(bs.seat_number ORDER BY bs.seat_number) AS seats
     FROM bookings bk
     JOIN buses b ON bk.bus_id = b.bus_id
     LEFT JOIN booking_seats bs ON bk.booking_id = bs.booking_id
     WHERE bk.user_id = ?
     GROUP BY bk.booking_id
     ORDER BY bk.booking_id DESC"
);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
?>
<section class="container">
    <h1>My bookings</h1>
    <div class="table-wrap">
    <table>
        <tr><th>ID</th><th>Bus</th><th>Date</th><th>Passenger</th><th>Seats</th><th>Total</th></tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= (int)$row["booking_id"] ?></td>
            <td><?= htmlspecialchars($row["bus_name"]) ?></td>
            <td><?= htmlspecialchars($row["journey_date"]) ?></td>
            <td><?= htmlspecialchars($row["passenger_name"]) ?></td>
            <td><?= htmlspecialchars($row["seats"] ?? "") ?></td>
            <td>₹<?= (int)$row["total_amount"] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    </div>
</section>
<?php include "includes/footer.php"; ?>