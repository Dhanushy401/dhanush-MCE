<?php
require_once "config/database.php";
include "includes/header.php";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!preg_match('/^[A-Za-z ]{3,50}$/', $name)) {
        $message = "Name must contain 3–50 letters and spaces.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Enter a valid email address.";
    } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*[0-9]).{6,}$/', $password)) {
        $message = "Password must be at least 6 characters and contain a letter and a number.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $name, $email, $hash);
            $stmt->execute();
            $message = "Registration successful. You can now log in.";
        } catch (mysqli_sql_exception $e) {
            $message = "That email address is already registered.";
        }
    }
}
?>
<section class="form-card">
    <h1>Create account</h1>
    <p class="notice"><?= htmlspecialchars($message) ?></p>
    <form method="POST" onsubmit="return validateRegistration();">
        <label>Name</label><input id="regName" name="name" required>
        <small id="regNameError" class="error"></small>
        <label>Email</label><input type="email" id="regEmail" name="email" required>
        <small id="regEmailError" class="error"></small>
        <label>Password</label><input type="password" id="regPassword" name="password" required>
        <small id="regPasswordError" class="error"></small>
        <button>Create account</button>
    </form>
    <p>Already registered? <a href="login.php">Login</a></p>
</section>
<?php include "includes/footer.php"; ?>