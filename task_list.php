<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Task List with Timer Modes</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #1e1e1e;
            color: #ffffff;
            margin: 0;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #2d2d2d;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        
        h2 {
            color: #4CAF50;
            margin-bottom: 20px;
        }
        
        .add-link {
            display: inline-block;
            padding: 8px 15px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        
        .add-link:hover {
            background-color: #45a049;
        }
        
        .task-item {
            border-bottom: 1px solid #444;
            padding: 15px 0;
        }
        
        .task-item:last-child {
            border-bottom: none;
        }
        
        .task-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .task-notes {
            color: #aaaaaa;
            font-style: italic;
            margin-bottom: 10px;
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
        
        .action-link {
            display: inline-block;
            margin: 5px 10px 5px 0;
            padding: 5px 10px;
            background-color: #3a3a3a;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .action-link:hover {
            background-color: #4a4a4a;
        }
        
        .back-link {
            display: block;
            margin-top: 20px;
            color: #4CAF50;
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Your Tasks</h2>
        <a href="add_task.php" class="add-link">+ Add New Task</a>
        <ul style="list-style-type: none; padding: 0;">
            <?php
            $tasks = $conn->query("SELECT * FROM tasks ORDER BY id DESC");
            while ($row = $tasks->fetch_assoc()) {
                echo "<li class='task-item'>
                    <div class='task-title'>{$row['title']}</div>
                    <div class='task-notes'>{$row['notes']}</div>
                    <a class='timer-link' href='timer_modes.php?task_id={$row['id']}'>Start Timer with Modes</a>
                    <a class='timer-link' href='timer.php?task_id={$row['id']}'>Start Classic Pomodoro</a>
                    <a class='action-link' href='complete_task.php?id={$row['id']}'>Mark Complete</a>
                    <a class='action-link' href='delete_task.php?id={$row['id']}'>Delete</a>
                </li>";
            }
            ?>
        </ul>
        <a href="index.php" class="back-link">⬅ Back to Dashboard</a>
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
