<?php
include 'db.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username && $password) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashed);

        if ($stmt->execute()) {
            $message = "✅ Signup successful. You can now log in.";
        } else {
            $message = "❌ Username already taken.";
        }
    } else {
        $message = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Signup</title></head>
<body>
    <h2>📝 Signup</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Sign Up</button>
    </form>
    <p><?= $message ?></p>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</body>
</html>
