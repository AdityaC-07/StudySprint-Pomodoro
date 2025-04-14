<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Timer Modes</title>
    <style>
        .mode-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            background-color: #f9f9f9;
        }
        .mode-card:hover {
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .start-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h2>🕒 Select Timer Mode</h2>
    
    <div class="mode-card">
        <h3>Standard Pomodoro</h3>
        <p>• Work: 25 minutes</p>
        <p>• Short Break: 5 minutes</p>
        <p>• Long Break: 15 minutes (after 4 pomodoros)</p>
        <a class="start-btn" href="pomodoro_timer.php?task_id=<?= $_GET['task_id'] ?>&mode=standard">Start</a>
    </div>
    
    <div class="mode-card">
        <h3>Extended Focus</h3>
        <p>• Work: 50 minutes</p>
        <p>• Short Break: 10 minutes</p>
        <p>• Long Break: 30 minutes (after 3 sessions)</p>
        <a class="start-btn" href="pomodoro_timer.php?task_id=<?= $_GET['task_id'] ?>&mode=extended&duration=3000">Start</a>
    </div>
    
    <div class="mode-card">
        <h3>Quick Sprints</h3>
        <p>• Work: 15 minutes</p>
        <p>• Short Break: 3 minutes</p>
        <p>• Long Break: 10 minutes (after 6 sessions)</p>
        <a class="start-btn" href="pomodoro_timer.php?task_id=<?= $_GET['task_id'] ?>&mode=quick&duration=900">Start</a>
    </div>
    
    <p><a href="index.php">⬅ Back to Tasks</a></p>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <title>Custom Timer</title>
    <style>
        .custom-timer-form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .submit-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>⏱️ Custom Timer</h2>
    
    <div class="custom-timer-form">
        <form id="customTimer" action="pomodoro_timer.php" method="GET">
            <input type="hidden" name="task_id" value="<?= $_GET['task_id'] ?>">
            <input type="hidden" name="mode" value="custom">
            
            <div class="form-group">
                <label for="duration">Work Duration (minutes):</label>
                <input type="number" id="duration" name="custom_minutes" min="1" max="120" value="25">
            </div>
            
            <div class="form-group">
                <label for="short_break">Short Break (minutes):</label>
                <input type="number" id="short_break" name="short_break" min="1" max="30" value="5">
            </div>
            
            <div class="form-group">
                <label for="long_break">Long Break (minutes):</label>
                <input type="number" id="long_break" name="long_break" min="5" max="60" value="15">
            </div>
            
            <button type="submit" class="submit-btn">Start Timer</button>
        </form>
    </div>
    
    <script>
        document.getElementById('customTimer').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const minutes = parseInt(document.getElementById('duration').value);
            const shortBreak = parseInt(document.getElementById('short_break').value);
            const longBreak = parseInt(document.getElementById('long_break').value);
            
            // Convert minutes to seconds
            const duration = minutes * 60;
            const shortBreakSecs = shortBreak * 60;
            const longBreakSecs = longBreak * 60;
            
            // Get the form values
            const taskId = this.elements['task_id'].value;
            
            // Redirect to the timer page with custom parameters
            window.location.href = `pomodoro_timer.php?task_id=${taskId}&mode=custom&duration=${duration}&short_break=${shortBreakSecs}&long_break=${longBreakSecs}`;
        });
    </script>
</body>
</html>