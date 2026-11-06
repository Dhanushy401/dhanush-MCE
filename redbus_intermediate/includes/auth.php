<?php
// Include this file on pages that require a logged-in user.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>