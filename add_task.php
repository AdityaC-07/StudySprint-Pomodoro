// Modify add_task.php to include categories
<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head><title>Add Task</title></head>
<body>
<h2>Add a New Task</h2>
<form method="POST">
    Title: <input type="text" name="title" required><br><br>
    Category: 
    <select name="category">
        <option value="work">Work</option>
        <option value="study">Study</option>
        <option value="personal">Personal</option>
        <option value="other">Other</option>
    </select><br><br>
    Notes: <textarea name="notes"></textarea><br><br>
    <button type="submit" name="add">Add Task</button>
</form>

<?php
if (isset($_POST['add'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $notes = $conn->real_escape_string($_POST['notes']);
    $category = $conn->real_escape_string($_POST['category']);
    
    // Check if category column exists in tasks table
    $result = $conn->query("SHOW COLUMNS FROM tasks LIKE 'category'");
    if ($result && $result->num_rows == 0) {
        // Add category column if it doesn't exist
        $conn->query("ALTER TABLE tasks ADD COLUMN category VARCHAR(50)");
    }
    
    $conn->query("INSERT INTO tasks (title, notes, category) VALUES ('$title', '$notes', '$category')");
    header("Location: index.php");
}
?>
</body>
</html>

// Also modify index.php to display categories
// Add this to your task listing loop in index.php:
<?php
$tasks = $conn->query("SELECT * FROM tasks ORDER BY id DESC");
while ($row = $tasks->fetch_assoc()) {
    $category = isset($row['category']) ? $row['category'] : 'uncategorized';
    echo "<li>
        <span class='category-badge $category'>$category</span>
        <strong>{$row['title']}</strong><br>
        <em>{$row['notes']}</em><br>
        <a href='timer.php?task_id={$row['id']}'>Start Pomodoro</a> |
        <a href='complete_task.php?id={$row['id']}'>Mark Complete</a> |
        <a href='delete_task.php?id={$row['id']}'>Delete</a>
    </li><hr>";
}
?>
