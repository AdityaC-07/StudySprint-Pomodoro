<?php
$task_id = $_GET['task_id'];
$mode = $_GET['mode'] ?? 'standard';
$custom_duration = $_GET['duration'] ?? null;

// Set durations based on mode
switch ($mode) {
    case 'extended':
        $work_duration = 3000; // 50 minutes
        $short_break_duration = 600; // 10 minutes
        $long_break_duration = 1800; // 30 minutes
        $sessions_until_long_break = 3;
        break;
    case 'quick':
        $work_duration = 900; // 15 minutes
        $short_break_duration = 180; // 3 minutes
        $long_break_duration = 600; // 10 minutes
        $sessions_until_long_break = 6;
        break;
    case 'standard':
    default:
        $work_duration = 1500; // 25 minutes
        $short_break_duration = 300; // 5 minutes
        $long_break_duration = 900; // 15 minutes
        $sessions_until_long_break = 4;
        break;
}

// Override with custom duration if provided
if ($custom_duration) {
    $work_duration = intval($custom_duration);
}

// Convert to minutes for display
$display_minutes = $work_duration / 60;

// Store session info in a session variable
session_start();
if (!isset($_SESSION['pomodoro_count'])) {
    $_SESSION['pomodoro_count'] = 0;
}
if (!isset($_SESSION['sessions_until_long_break'])) {
    $_SESSION['sessions_until_long_break'] = $sessions_until_long_break;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pomodoro Timer</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }
        #timer {
            font-size: 72px;
            margin: 30px 0;
        }
        .timer-label {
            font-size: 24px;
            font-weight: bold;
            color: #d9534f;
        }
        .control-btn {
            background-color: #0275d8;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 5px;
            cursor: pointer;
        }
        .session-info {
            margin-top: 20px;
            font-size: 16px;
            color: #666;
        }
        .mode-badge {
            display: inline-block;
            padding: 5px 10px;
            background-color: #f8f9fa;
            border-radius: 15px;
            font-size: 14px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="mode-badge"><?= ucfirst($mode) ?> Mode</div>
    <h2>⏱ <span class="timer-label">Focus Time</span></h2>
    <p id="timer"><?= floor($display_minutes) ?>:00</p>
    
    <button id="pauseBtn" class="control-btn">Pause</button>
    <button id="resetBtn" class="control-btn">Reset</button>
    
    <div class="session-info">
        <p>Pomodoros completed today: <?= $_SESSION['pomodoro_count'] ?></p>
        <p>Next long break in: <?= $_SESSION['sessions_until_long_break'] - ($_SESSION['pomodoro_count'] % $_SESSION['sessions_until_long_break']) ?> sessions</p>
    </div>
    
    <script>
        let seconds = <?= $work_duration ?>;
        let isPaused = false;
        const timerDisplay = document.getElementById("timer");
        const pauseBtn = document.getElementById("pauseBtn");
        const resetBtn = document.getElementById("resetBtn");
        
        pauseBtn.addEventListener("click", function() {
            isPaused = !isPaused;
            this.textContent = isPaused ? "Resume" : "Pause";
        });
        
        resetBtn.addEventListener("click", function() {
            seconds = <?= $work_duration ?>;
            updateTimerDisplay();
        });
        
        function updateTimerDisplay() {
            let mins = Math.floor(seconds / 60);
            let secs = seconds % 60;
            timerDisplay.textContent = `${mins}:${secs < 10 ? '0' + secs : secs}`;
        }
        
        const interval = setInterval(() => {
            if (!isPaused) {
                updateTimerDisplay();
                if (seconds === 0) {
                    clearInterval(interval);
                    window.location.href = "log_session.php?task_id=<?= $task_id ?>&duration=<?= $work_duration ?>&mode=<?= $mode ?>";
                }
                seconds--;
            }
        }, 1000);
    </script>
</body>
</html>