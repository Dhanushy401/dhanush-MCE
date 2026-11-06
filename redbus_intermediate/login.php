<?php
require_once "config/database.php";
$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $stmt = $conn->prepare("SELECT user_id, name, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {
        session_regenerate_id(true);
        $_SESSION["user_id"] = (int)$user["user_id"];
        $_SESSION["user_name"] = $user["name"];
        header("Location: dashboard.php");
        exit;
    }
    $message = "Email or password is incorrect.";
}
include "includes/header.php";
?>
<section class="form-card">
    <h1>Login</h1>
    <p class="error"><?= htmlspecialchars($message) ?></p>
    <form method="POST">
        <label>Email</label><input type="email" name="email" required>
        <label>Password</label><input type="password" name="password" required>
        <button>Login</button>
    </form>
    <p>New user? <a href="register.php">Register here</a></p>
</section>
<?php include "includes/footer.php"; ?>