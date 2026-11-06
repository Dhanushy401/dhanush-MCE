<?php
require_once "config/database.php";
include "includes/header.php";
$file = __DIR__ . "/data/feedback.txt";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["add"])) {
        $text = trim($_POST["feedback"] ?? "");
        if ($text !== "") {
            file_put_contents($file, $text . PHP_EOL, FILE_APPEND | LOCK_EX);
            $message = "Feedback added.";
        }
    } elseif (isset($_POST["update"])) {
        $index = (int)$_POST["index"];
        $newText = trim($_POST["new_text"] ?? "");
        $lines = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
        if (isset($lines[$index]) && $newText !== "") {
            $lines[$index] = $newText;
            file_put_contents($file, implode(PHP_EOL, $lines) . (count($lines) ? PHP_EOL : ""), LOCK_EX);
            $message = "Feedback updated.";
        }
    } elseif (isset($_POST["delete"])) {
        $index = (int)$_POST["index"];
        $lines = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
        if (isset($lines[$index])) {
            array_splice($lines, $index, 1);
            file_put_contents($file, implode(PHP_EOL, $lines) . (count($lines) ? PHP_EOL : ""), LOCK_EX);
            $message = "Feedback deleted.";
        }
    }
}
$lines = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];
?>
<section class="form-card wide">
    <h1>Feedback file management</h1>
    <p class="notice"><?= htmlspecialchars($message) ?></p>
    <form method="POST">
        <label>New feedback</label>
        <textarea name="feedback" required></textarea>
        <button name="add">Add feedback</button>
    </form>
    <h2>Saved feedback</h2>
    <?php foreach ($lines as $index => $line): ?>
        <div class="feedback-row">
            <p><?= htmlspecialchars($line) ?></p>
            <form method="POST" class="inline-form">
                <input type="hidden" name="index" value="<?= $index ?>">
                <input name="new_text" placeholder="New text" required>
                <button name="update">Update</button>
                <button class="danger" name="delete" value="1">Delete</button>
            </form>
        </div>
    <?php endforeach; ?>
</section>
<?php include "includes/footer.php"; ?>