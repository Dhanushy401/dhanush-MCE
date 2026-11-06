<?php
// Simple classroom CRUD page. In a real system, add an administrator role.
require_once "config/database.php";
include "includes/auth.php";
include "includes/header.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? "";
    $busName = trim($_POST["bus_name"] ?? "");
    $busType = trim($_POST["bus_type"] ?? "");
    $fare = (int)($_POST["fare"] ?? 0);
    $routeId = (int)($_POST["route_id"] ?? 0);
    $departure = $_POST["departure"] ?? "08:00";
    $arrival = $_POST["arrival"] ?? "18:00";
    $seats = (int)($_POST["total_seats"] ?? 40);

    if ($action === "delete") {
        $id = (int)$_POST["bus_id"];
        $stmt = $conn->prepare("DELETE FROM buses WHERE bus_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $message = "Bus deleted (only if it has no dependent bookings).";
    } elseif ($busName !== "" && $busType !== "" && $fare > 0 && $routeId > 0 && $seats > 0 && $seats <= 40) {
        if ($action === "update") {
            $id = (int)$_POST["bus_id"];
            $stmt = $conn->prepare("UPDATE buses SET bus_name=?, bus_type=?, departure=?, arrival=?, fare=?, total_seats=?, route_id=? WHERE bus_id=?");
            $stmt->bind_param("ssssiiii", $busName, $busType, $departure, $arrival, $fare, $seats, $routeId, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO buses (bus_name,bus_type,departure,arrival,fare,total_seats,route_id) VALUES (?,?,?,?,?,?,?)");
            $stmt->bind_param("ssssiii", $busName, $busType, $departure, $arrival, $fare, $seats, $routeId);
        }
        $stmt->execute();
        $message = "Bus saved.";
    } else {
        $message = "Please enter valid bus details.";
    }
}
$routes = $conn->query("SELECT route_id, source, destination FROM routes ORDER BY source");
$buses = $conn->query("SELECT b.*, r.source, r.destination FROM buses b JOIN routes r ON b.route_id=r.route_id ORDER BY b.bus_id");
?>
<section class="container">
    <h1>Bus management – add, edit and delete</h1>
    <p class="notice"><?= htmlspecialchars($message) ?></p>
    <form method="POST" class="form-card">
        <input type="hidden" name="action" value="add">
        <label>Bus name</label><input name="bus_name" required>
        <label>Bus type</label><input name="bus_type" required>
        <label>Departure</label><input type="time" name="departure" value="08:00" required>
        <label>Arrival</label><input type="time" name="arrival" value="18:00" required>
        <label>Fare</label><input type="number" name="fare" min="1" required>
        <label>Total seats (max 40)</label><input type="number" name="total_seats" min="1" max="40" value="40" required>
        <label>Route</label><select name="route_id"><?php while($r=$routes->fetch_assoc()): ?><option value="<?= $r["route_id"] ?>"><?= htmlspecialchars($r["source"]." to ".$r["destination"]) ?></option><?php endwhile; ?></select>
        <button>Add bus</button>
    </form>
    <h2>Existing buses</h2>
    <?php while ($b = $buses->fetch_assoc()): ?>
    <form method="POST" class="bus-card edit-row">
        <input type="hidden" name="bus_id" value="<?= $b["bus_id"] ?>">
        <input type="hidden" name="action" value="update">
        <input name="bus_name" value="<?= htmlspecialchars($b["bus_name"]) ?>" required>
        <input name="bus_type" value="<?= htmlspecialchars($b["bus_type"]) ?>" required>
        <input type="time" name="departure" value="<?= htmlspecialchars($b["departure"]) ?>">
        <input type="time" name="arrival" value="<?= htmlspecialchars($b["arrival"]) ?>">
        <input type="number" name="fare" value="<?= (int)$b["fare"] ?>" min="1">
        <input type="number" name="total_seats" value="<?= (int)$b["total_seats"] ?>" min="1" max="40">
        <select name="route_id">
            <?php $routeOptions = $conn->query("SELECT route_id,source,destination FROM routes"); while($r=$routeOptions->fetch_assoc()): ?>
            <option value="<?= $r["route_id"] ?>" <?= (int)$r["route_id"] === (int)$b["route_id"] ? "selected" : "" ?>><?= htmlspecialchars($r["source"]." to ".$r["destination"]) ?></option>
            <?php endwhile; ?>
        </select>
        <button>Update</button>
        <button class="danger" name="action" value="delete" formnovalidate>Delete</button>
    </form>
    <?php endwhile; ?>
</section>
<?php include "includes/footer.php"; ?>