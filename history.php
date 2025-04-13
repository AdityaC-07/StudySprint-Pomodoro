<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Focus History</title></head>
<body>
<h2>📜 Focus Session History</h2>
<ul>
<?php
$result = $conn->query("SELECT history.*, tasks.title FROM history LEFT JOIN tasks ON tasks.id = history.task_id ORDER BY timestamp DESC");
while ($row = $result->fetch_assoc()) {
    $min = round($row['duration'] / 60, 1);
    $date = date("d M Y, h:i A", $row['timestamp']);
    echo "<li>{$row['title']} – $min mins on $date</li>";
}
?>
</ul>
<a href="index.php">⬅️ Back</a>
</body>
</html>
