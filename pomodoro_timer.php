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
            font-family: 'Arial', sans-serif;
            background-color: #1e1e1e;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .timer-container {
            text-align: center;
            width: 300px;
            background-color: #2d2d2d;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }
        .app-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .app-title {
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
            margin: 0;
        }
        .close-icon {
            cursor: pointer;
            font-size: 20px;
        }
        .circle-timer {
            position: relative;
            width: 200px;
            height: 200px;
            margin: 0 auto 20px;
        }
        .timer-circle {
            fill: none;
            stroke-width: 8;
        }
        .timer-background {
            stroke: #3a3a3a;
        }
        .timer-progress {
            stroke: #e53935;
            stroke-linecap: round;
            transform: rotate(-90deg);
            transform-origin: center;
            transition: stroke-dashoffset 1s linear;
        }
        .timer-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }
        .timer-time {
            font-size: 36px;
            font-weight: bold;
            margin: 0;
        }
        .timer-label {
            font-size: 14px;
            color: #aaa;
            text-transform: uppercase;
            margin-top: 5px;
        }
        .control-button {
            background: none;
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #3a3a3a;
            color: white;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .control-button:hover {
            background-color: #4a4a4a;
        }
        .session-info {
            margin-top: 15px;
            font-size: 12px;
            color: #aaa;
        }
        .mode-badge {
            background-color: #4a4a4a;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            margin-top: 10px;
            display: inline-block;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
            font-size: 12px;
            margin-top: 10px;
            display: block;
        }
    </style>
</head>
<body>
<div class="timer-container">
    <div class="app-header">
        <p class="app-title">Pomodoro</p>
        <span class="close-icon">×</span>
    </div>
    <div class="circle-timer">
        <svg width="200" height="200" viewBox="0 0 200 200">
            <circle class="timer-circle timer-background" cx="100" cy="100" r="90" />
            <circle id="progress-ring" class="timer-circle timer-progress" cx="100" cy="100" r="90" />
        </svg>
        <div class="timer-text">
            <p id="timer" class="timer-time"><?= floor($work_duration / 60) ?>:00</p>
            <p class="timer-label">WORK</p>
        </div>
    </div>
    <button id="controlBtn" class="control-button">
        ▶
    </button>
    <div class="session-info">
        <span class="mode-badge"><?= ucfirst($mode) ?> Mode</span>
        <p>Session <?= $_SESSION['pomodoro_count'] + 1 ?> of <?= $_SESSION['sessions_until_long_break'] ?></p>
    </div>
    <a href="index.php">⬅ Back to Tasks</a>
</div>
<script>
// Circle properties
const circle = document.getElementById('progress-ring');
const radius = circle.r.baseVal.value;
const circumference = 2 * Math.PI * radius;

// Set initial properties
circle.style.strokeDasharray = `${circumference} ${circumference}`;
circle.style.strokeDashoffset = 0;

// Timer variables
let seconds = <?= $work_duration ?>;
const totalSeconds = seconds;
let elapsedTime = 0;
let startTime = null;
let isPaused = true;
let intervalId = null;
const timerDisplay = document.getElementById("timer");
const controlBtn = document.getElementById("controlBtn");

// Update the timer display initially
updateTimerDisplay();

// Update the circle progress
function setProgress(percent) {
    const offset = circumference - (percent / 100 * circumference);
    circle.style.strokeDashoffset = offset;
}

// Update the timer display
function updateTimerDisplay() {
    let mins = Math.floor(seconds / 60);
    let secs = seconds % 60;
    timerDisplay.textContent = `${mins}:${secs < 10 ? '0' + secs : secs}`;
    const percent = ((totalSeconds - seconds) / totalSeconds) * 100;
    setProgress(percent);
}

// Toggle timer
controlBtn.addEventListener("click", function() {
    isPaused = !isPaused;
    if (isPaused) {
        clearInterval(intervalId);
        if (startTime) {
            elapsedTime += (Date.now() - startTime) / 1000;
        }
        this.innerHTML = "▶ ";
    } else {
        startTime = Date.now();
        this.innerHTML = "⏸";
        intervalId = setInterval(updateTimer, 1000);
    }
});

// Handle timer update
function updateTimer() {
    if (seconds > 0) {
        seconds--;
        updateTimerDisplay();
    } else {
        clearInterval(intervalId);
        elapsedTime += (Date.now() - startTime) / 1000;
        const actualDuration = Math.round(elapsedTime);
        setTimeout(() => {
            window.location.href = `log_session.php?task_id=<?= $task_id ?>&duration=${actualDuration}`;
        }, 1500);
    }
}
</script>
</body>
</html>
