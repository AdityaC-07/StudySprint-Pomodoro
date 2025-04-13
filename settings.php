<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

include 'db.php';
$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newpass = $_POST['new_password'];
    $hash = password_hash($newpass, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $hash, $_SESSION['user_id']);

    if ($stmt->execute()) {
        $message = "✅ Password updated successfully!";
    } else {
        $message = "❌ Failed to update password.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Settings</title></head>
<body>
    <h2>⚙️ Settings</h2>
    <p>Change your password below:</p>

    <form method="POST">
        <input type="password" name="new_password" placeholder="New Password" required><br><br>
        <button type="submit">Update Password</button>
    </form>

    <p><?= $message ?></p>
    <p><a href="index.php">← Back to Dashboard</a></p>
</body>
</html>
