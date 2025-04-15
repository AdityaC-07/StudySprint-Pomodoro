<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Focus History</title>
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
            text-align: center;
            margin-bottom: 30px;
        }
        
        ul {
            list-style-type: none;
            padding: 0;
        }
        
        li {
            padding: 12px;
            border-bottom: 1px solid #444;
            margin-bottom: 10px;
        }
        
        li:last-child {
            border-bottom: none;
        }
        
        .history-date {
            color: #aaaaaa;
            font-size: 0.9em;
            display: block;
            margin-top: 5px;
        }
        
        .history-duration {
            color: #4CAF50;
            font-weight: bold;
        }
        
        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            padding: 10px;
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🍵 Focus Session History</h2>
        <ul>
            <?php
            $result = $conn->query("SELECT history.*, tasks.title FROM history LEFT JOIN tasks ON tasks.id = history.task_id ORDER BY timestamp DESC");
            while ($row = $result->fetch_assoc()) {
                $min = round($row['duration'] / 60, 1);
                $date = date("d M Y, h:i A", $row['timestamp']);
                $title = isset($row['title']) ? $row['title'] : 'Unknown Task';
                
                echo "<li>
                    <strong>{$title}</strong> – 
                    <span class='history-duration'>{$min} mins</span>
                    <span class='history-date'>{$date}</span>
                </li>";
            }
            ?>
        </ul>
        <a href="index.php" class="back-link">🍵 Back to Dashboard</a>
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
