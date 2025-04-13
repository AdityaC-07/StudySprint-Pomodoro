<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Add Task</title></head>
<body>
<h2>Add a New Task</h2>
<form method="POST">
    Title: <input type="text" name="title" required><br><br>
    Notes: <textarea name="notes"></textarea><br><br>
    <button type="submit" name="add">Add Task</button>
</form>

<?php
if (isset($_POST['add'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $notes = $conn->real_escape_string($_POST['notes']);
    $conn->query("INSERT INTO tasks (title, notes) VALUES ('$title', '$notes')");
    header("Location: index.php");
}
?>
</body>
</html>
