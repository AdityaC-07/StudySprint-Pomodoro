<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Task</title>
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
        
        .container {
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
        
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        
        label {
            margin-bottom: 5px;
            display: block;
            color: #aaaaaa;
        }
        
        input[type="text"], select, textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #444;
            background-color: #3a3a3a;
            color: #ffffff;
            box-sizing: border-box;
        }
        
        textarea {
            height: 100px;
            resize: vertical;
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
        
        a {
            color: #4CAF50;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add a New Task</h2>
        <form method="POST">
            <div>
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
            </div>
            
            <div>
                <label for="category">Category:</label>
                <select id="category" name="category">
                    <option value="work">Work</option>
                    <option value="study">Study</option>
                    <option value="personal">Personal</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div>
                <label for="notes">Notes:</label>
                <textarea id="notes" name="notes"></textarea>
            </div>
            
            <button type="submit" name="add">Add Task</button>
        </form>
        <a href="index.php">⬅ Back to Tasks</a>
    </div>

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
    
    $conn->query("INSERT INTO tasks (title, notes, category) VALUES
    ('$title', '$notes', '$category')");
    
    header("Location: index.php");
}
?>
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
