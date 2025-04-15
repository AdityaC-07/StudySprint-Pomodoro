<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';
include 'theme.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>StudySprint</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: <?= $bodyBg ?>;
            color: <?= $textColor ?>;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ccc;
        }

        .user-info {
            font-size: 14px;
            color: <?= $textColor ?>;
        }

        .user-info a {
            color: <?= $accent ?>;
            text-decoration: none;
            margin-left: 10px;
        }

        h1, h2, h3 {
            color: <?= $accent ?>;
            font-weight: normal;
        }

        .summary-box, .task-item {
            background-color: <?= $cardBg ?>;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .action-btn {
            display: inline-block;
            background-color: <?= $accent ?>;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            margin-right: 10px;
            margin-bottom: 10px;
        }

        .action-btn:hover {
            background-color: <?= $hoverAccent ?>;
        }

        .task-title {
            font-size: 18px;
            margin: 0 0 5px;
        }

        .task-notes {
            color: <?= ($theme === 'dark') ? '#aaa' : '#666' ?>;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .task-actions a {
            color: <?= $accent ?>;
            text-decoration: none;
            margin-right: 15px;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ccc;
        }

        .footer a {
            color: <?= $accent ?>;
            text-decoration: none;
            margin: 0 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>🎯 StudySprint</h1>
        <div class="user-info">
            👤 <?= $_SESSION['username'] ?>
            <a href="settings.php">Settings</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <?php
    $today = date('Y-m-d');
    $todayStart = strtotime($today . ' 00:00:00');
    $todayEnd = strtotime($today . ' 23:59:59');
    $summary = $conn->query("SELECT SUM(duration) as total FROM history WHERE timestamp BETWEEN $todayStart AND $todayEnd");
    $row = $summary->fetch_assoc();
    $minutes = round(($row['total'] ?? 0) / 60, 1);
    ?>

    <div class="summary-box">
        <h3>🕒 Today's Focus Time: <?= $minutes ?> minutes</h3>
        <a href="progress.php" class="action-btn">📈 View Progress</a>
        <a href="task_list.php" class="action-btn">🕒 Tasks with Timer Modes</a>
    </div>

    <div>
        <h2>Your Tasks</h2>
        <div style="margin-bottom: 15px;">
            <a href="add_task.php" class="action-btn">+ Add New Task</a>
        </div>

        <ul style="list-style-type: none; padding: 0;">
        <?php
        $tasks = $conn->query("SELECT * FROM tasks ORDER BY id DESC");
        if ($tasks->num_rows > 0) {
            while ($row = $tasks->fetch_assoc()) {
                ?>
                <li class="task-item">
                    <h3 class="task-title"><?= $row['title'] ?></h3>
                    <div class="task-notes"><?= $row['notes'] ?></div>
                    <div class="task-actions">
                        <a href="timer.php?task_id=<?= $row['id'] ?>">▶️ Start Pomodoro</a>
                        <a href="complete_task.php?id=<?= $row['id'] ?>">✓ Complete</a>
                        <a href="delete_task.php?id=<?= $row['id'] ?>">🗑️ Delete</a>
                    </div>
                </li>
                <?php
            }
        } else {
            echo '<p style="text-align: center; color: #aaa;">No tasks yet. Add your first task to get started!</p>';
        }
        ?>
        </ul>
    </div>

    <div class="footer">
        <a href="history.php">📜 View Focus History</a>
        <a href="enhanced_history.php">📊 Enhanced History</a>
        <a href="task_statistics.php">📈 Task Statistics</a>
    </div>
</div>
</body>
</html>
