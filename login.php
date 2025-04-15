<?php
include 'db.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            header("Location: index.php");
            exit;
        } else {
            $message = "❌ Incorrect password.";
        }
    } else {
        $message = "❌ User not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        input, button {
            padding: 10px;
            margin: 10px;
            border: none;
            border-radius: 5px;
        }
        input {
            background-color: #1e1e1e;
            color: #ffffff;
        }
        button {
            background-color: #6200ea;
            color: #ffffff;
            cursor: pointer;
        }
        button:hover {
            background-color: #3700b3;
        }
        a {
            color: #bb86fc;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h2>🔐 Login</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Log In</button>
    </form>
    <p><?= $message ?></p>
    <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
</body>
</html>

<?php include 'theme.php'; ?>
<style>
    body {
        background-color: <?= $bodyBg ?>;
        color: <?= $textColor ?>;
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 0;
    }

    .action-btn, button {
        background-color: <?= $accent ?>;
        color: #fff;
        border: none;
        padding: 10px 16px;
        border-radius: 4px;
        cursor: pointer;
    }

    .card, .container, .timer-container {
        background-color: <?= $cardBg ?>;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 20px;
    }

    a {
        color: <?= $accent ?>;
    }

    .action-btn:hover, button:hover {
        background-color: #a07800;
    }
</style>
