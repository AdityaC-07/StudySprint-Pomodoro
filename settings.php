<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';
$message = "";

// Handle password update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['new_password'])) {
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

// Handle theme update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['theme'])) {
    $_SESSION['theme'] = $_POST['theme'];
    $message = "✅ Theme updated to " . htmlspecialchars($_POST['theme']) . "!";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Settings</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1e1e1e;
            color: #ffffff;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        
        .settings-container {
            width: 100%;
            max-width: 500px;
            background-color: #2d2d2d;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        
        h2 {
            color: #4CAF50;
            text-align: center;
            margin-bottom: 20px;
        }
        
        p {
            margin-bottom: 20px;
            color: #dddddd;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        input[type="password"], select {
            width: 100%;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #3a3a3a;
            color: #ffffff;
            box-sizing: border-box;
        }
        
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }
        
        button:hover {
            background-color: #45a049;
        }
        
        .message {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
        }
        
        .success {
            background-color: rgba(76, 175, 80, 0.2);
            color: #4CAF50;
        }
        
        .error {
            background-color: rgba(255, 87, 34, 0.2);
            color: #FF5722;
        }
        
        a {
            color: #4CAF50;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 30px;
        }
        
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="settings-container">
        <h2>🍵 Settings</h2>
        <p>Change your password below:</p>
        <form method="POST">
            <input type="password" name="new_password" placeholder="New Password" required>
            <button type="submit">Update Password</button>
        </form>

        <p>Change your theme below:</p>
        <form method="POST">
            <select name="theme">
                <option value="dark" <?= ($_SESSION['theme'] === 'dark') ? 'selected' : '' ?>>Dark</option>
                <option value="green" <?= ($_SESSION['theme'] === 'green') ? 'selected' : '' ?>>Light</option>
                <option value="blue" <?= ($_SESSION['theme'] === 'blue') ? 'selected' : '' ?>>Blue</option>
                <option value="solarized" <?= ($_SESSION['theme'] === 'solarized') ? 'selected' : '' ?>>Solarized</option>
            </select>
            <button type="submit">Update Theme</button>
        </form>

        <?php if ($message): ?>
            <div class="message <?php echo strpos($message, '✅') !== false ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <a href="index.php">← Back to Dashboard</a>
    </div>
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
