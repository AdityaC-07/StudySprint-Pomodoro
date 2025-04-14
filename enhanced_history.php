<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Focus History</title>
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.6;
            margin: 20px;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        li {
            margin-bottom: 8px;
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .mode-badge {
            display: inline-block;
            background-color: #f0f0f0;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 12px;
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <h2>📜 Focus Session History</h2>
    <ul>
        <?php
        $result = $conn->query("SELECT history.*, tasks.title FROM history 
                               LEFT JOIN tasks ON tasks.id = history.task_id 
                               ORDER BY timestamp DESC");
        
        while ($row = $result->fetch_assoc()) {
            $min = round($row['duration'] / 60, 1);
            
            // Get proper timestamp
            $timestamp = (int)$row['timestamp'];
            $date = date("d M Y, h:i A", $timestamp);
            
            // Get title, with fallback
            $title = $row['title'] ?: '*';
            
            // Extract mode if available in notes
            $mode_html = '';
            if (isset($row['notes']) && strpos($row['notes'], 'Mode:') !== false) {
                $mode = str_replace('Mode: ', '', $row['notes']);
                $mode = ucfirst($mode);
                $mode_html = "<span class='mode-badge'>$mode</span>";
            }
            
            echo "<li>{$title} {$mode_html} – {$min} mins on {$date}</li>";
        }
        ?>
    </ul>
    <a href="index.php">⬅ Back</a>
</body>
</html>