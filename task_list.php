<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Task List with Timer Modes</title>
    <style>
        .task-item {
            border-bottom: 1px solid #eee;
            padding: 15px 0;
        }
        .timer-link {
            display: inline-block;
            margin: 5px 10px 5px 0;
            padding: 5px 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        .timer-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h2>Your Tasks</h2>
    <a href="add_task.php">+ Add New Task</a>
    <ul style="list-style-type: none; padding: 0;">
        <?php
        $tasks = $conn->query("SELECT * FROM tasks ORDER BY id DESC");
        while ($row = $tasks->fetch_assoc()) {
            echo "<li class='task-item'>
                <strong>{$row['title']}</strong><br>
                <em>{$row['notes']}</em><br>
                <a class='timer-link' href='timer_modes.php?task_id={$row['id']}'>Start Timer with Modes</a> | 
                <a href='timer.php?task_id={$row['id']}'>Start Classic Pomodoro</a> | 
                <a href='complete_task.php?id={$row['id']}'>Mark Complete</a> | 
                <a href='delete_task.php?id={$row['id']}'>Delete</a>
            </li>";
        }
        ?>
    </ul>
</body>
</html>