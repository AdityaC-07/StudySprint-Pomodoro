<?php
$break_type = $_GET['type'] ?? 'short';
$task_id = $_GET['task_id'];
$mode = $_GET['mode'] ?? 'standard';

// Set break durations
$short_break = 300; // 5 minutes in seconds
$long_break = 900;  // 15 minutes in seconds

$duration = ($break_type === 'long') ? $long_break : $short_break;
$minutes = $duration / 60;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Break Time</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
            background-color: #e9f7ef;
        }
        #break-timer {
            font-size: 72px;
            margin: 30px 0;
        }
        .break-label {
            font-size: 24px;
            font-weight: bold;
            color: #27ae60;
        }
        .skip-btn {
            background-color: #f39c12;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }
        .break-suggestion {
            margin: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <h2>🍵 <span class="break-label"><?= ($break_type === 'long') ? '15-Minute Long Break' : '5-Minute Short Break' ?></span></h2>
    <p id="break-timer"><?= ($break_type === 'long') ? '15:00' : '5:00' ?></p>
    
    <div class="break-suggestion">
        <h3>Break Suggestion</h3>
        <p><?= ($break_type === 'long') ? 
            'Take a longer break to recharge. Go for a short walk, grab a healthy snack, or do some light stretching.' : 
            'Take a moment to rest your eyes, stretch, or take a few deep breaths.' ?>
        </p>
    </div>
    
    <button id="skipBtn" class="skip-btn">Skip Break</button>
    
    <script>
        let seconds = <?= $duration ?>;
        const display = document.getElementById("break-timer");
        const skipBtn = document.getElementById("skipBtn");
        
        skipBtn.addEventListener("click", function() {
            window.location.href = "pomodoro_timer.php?task_id=<?= $task_id ?>&mode=<?= $mode ?>";
        });
        
        const interval = setInterval(() => {
            let mins = Math.floor(seconds / 60);
            let secs = seconds % 60;
            display.textContent = `${mins}:${secs < 10 ? '0' + secs : secs}`;
            
            if (seconds === 0) {
                clearInterval(interval);
                alert("Break complete! Ready for the next focus session?");
                window.location.href = "pomodoro_timer.php?task_id=<?= $task_id ?>&mode=<?= $mode ?>";
            }
            seconds--;
        }, 1000);
    </script>
</body>
</html>