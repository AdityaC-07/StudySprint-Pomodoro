<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<p>👤 Logged in as | <a href="settings.php">Settings</a>
<?= $_SESSION['username'] ?> | <a href="logout.php">Logout</a></p>

<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>StudySprint</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div style="text-align: center; margin-bottom: 30px;">
    <h1>🎯 StudySprint Pomodoro App</h1>
    <a href="progress.php" style="
    display: inline-block;
    margin-bottom: 20px;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: bold;
">
    📈 View Progress
</a>
</div>

    <!-- Daily Summary -->
    <?php
    $today = date('Y-m-d');
    $summary = $conn->query("SELECT SUM(duration) as total FROM history WHERE DATE(FROM_UNIXTIME(timestamp)) = '$today'");
    $minutes = round($summary->fetch_assoc()['total'] / 60, 1);
    echo "<h3>🕒 Today's Focus Time: $minutes minutes</h3>";
    ?>

    <!-- Task List -->
    <h2>Your Tasks</h2>
    <a href="add_task.php">+ Add New Task</a>
    <ul>
    <?php
    $tasks = $conn->query("SELECT * FROM tasks ORDER BY id DESC");
    while ($row = $tasks->fetch_assoc()) {
        echo "<li>
                <strong>{$row['title']}</strong><br>
                <em>{$row['notes']}</em><br>
                <a href='timer.php?task_id={$row['id']}'>Start Pomodoro</a> |
                <a href='complete_task.php?id={$row['id']}'>Mark Complete</a> |
                <a href='delete_task.php?id={$row['id']}'>Delete</a>
              </li><hr>";
    }
    ?>
    </ul>

    <a href="history.php">📜 View Focus History</a>
</body>
</html>
