<?php
include 'theme.php'; // Include theme.php at the very top
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
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['pomodoro_count'])) {
    $_SESSION['pomodoro_count'] = 0;
}
if (!isset($_SESSION['sessions_until_long_break'])) {
    $_SESSION['sessions_until_long_break'] = $sessions_until_long_break;
}

// Define theme-specific timer colors
switch ($theme) {
    case 'dark':
        $timerBgColor = "#3a3a3a";
        $timerProgressColor = "#e53935";
        break;
    case 'green':
        $timerBgColor = "#e0e0e0";
        $timerProgressColor = "#2e8b57";
        break;
    case 'light':
        $timerBgColor = "#f5f5f5";
        $timerProgressColor = "#c49700";
        break;
    case 'blue':
        $timerBgColor = "#e3f2fd";
        $timerProgressColor = "#1976d2";
        break;
    case 'solarized':
        $timerBgColor = "#eee8d5";
        $timerProgressColor = "#b58900";
        break;
    default:
        $timerBgColor = "#3a3a3a";
        $timerProgressColor = "#e53935";
}

// Set label color based on theme
$labelColor = ($theme === 'dark') ? '#aaa' : '#666';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pomodoro Timer</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: <?= $bodyBg ?>;
            color: <?= $textColor ?>;
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
            background-color: <?= $cardBg ?>;
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
            color: <?= $accent ?>;
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
            stroke: <?= $timerBgColor ?>;
        }
        .timer-progress {
            stroke: <?= $timerProgressColor ?>;
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
            color: <?= $labelColor ?>;
            text-transform: uppercase;
            margin-top: 5px;
        }
        .control-button {
            border: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: <?= $accent ?>;
            color: white;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            cursor: pointer;
        }
        .control-button:hover {
            background-color: <?= $hoverAccent ?>;
        }
        .session-info {
            margin-top: 15px;
            font-size: 12px;
            color: <?= $labelColor ?>;
        }
        .mode-badge {
            background-color: <?= $accent ?>22; /* Using hex opacity for background */
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 12px;
            margin-top: 10px;
            display: inline-block;
            color: <?= $textColor ?>;
        }
        a {
            color: <?= $accent ?>;
            text-decoration: none;
            font-size: 12px;
            margin-top: 15px;
            display: block;
        }
        .volume-control {
            margin-top: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .volume-control label {
            margin-right: 10px;
            font-size: 12px;
        }
        .volume-control input {
            width: 80px;
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
    <button id="controlBtn" class="control-button">▶</button>
    
    <!-- Volume control -->
    <div class="volume-control">
        <label for="volume">🔊</label>
        <input type="range" id="volume" min="0" max="1" step="0.1" value="0.7">
    </div>
    
    <!-- Audio element for the timer completion sound -->
    <audio id="timerSound" preload="auto">
        <source src="timer-sound.mp3" type="audio/mpeg">
        <!-- Fallback sound format -->
        <source src="timer-sound.ogg" type="audio/ogg">
        Your browser does not support the audio element.
    </audio>
    
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
const timerSound = document.getElementById("timerSound");
const volumeControl = document.getElementById("volume");

// Set volume based on range input
volumeControl.addEventListener("input", function() {
    timerSound.volume = this.value;
});

// Request notification permission
if (Notification.permission !== "granted") {
    Notification.requestPermission();
}

// Show a notification when the timer starts
function showStartNotification() {
    if (Notification.permission === "granted") {
        new Notification("Pomodoro Timer", {
            body: "Your Pomodoro session has started! Stay focused!",
            icon: "timer-icon.png" // Optional: Add an icon URL
        });
    }
}

// Play sound when timer ends
function playTimerCompleteSound() {
    timerSound.currentTime = 0; // Reset to start of audio file
    timerSound.play().catch(error => {
        console.error("Error playing sound:", error);
    });
}

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

// Handle timer update
function updateTimer() {
    if (seconds > 0) {
        seconds--;
        updateTimerDisplay();
    } else {
        clearInterval(intervalId);
        // Play completion sound before redirecting
        playTimerCompleteSound();
        
        if (startTime) {
            elapsedTime += (Date.now() - startTime) / 1000;
        }
        const actualDuration = Math.round(elapsedTime);
        
        // Delay redirect to allow sound to play
        setTimeout(() => {
            window.location.href = `log_session.php?task_id=<?= $task_id ?>&duration=${actualDuration}`;
        }, 1500); // Wait 1.5 seconds before redirecting
    }
}

// Update the timer display initially
updateTimerDisplay();

// Toggle timer
controlBtn.addEventListener("click", function() {
    isPaused = !isPaused;
    if (isPaused) {
        clearInterval(intervalId);
        if (startTime) {
            elapsedTime += (Date.now() - startTime) / 1000;
        }
        this.innerHTML = "▶";
    } else {
        startTime = Date.now();
        this.innerHTML = "⏸";
        intervalId = setInterval(updateTimer, 1000);
        // Show notification when timer starts
        showStartNotification();
    }
});

// Handle close button
document.querySelector(".close-icon").addEventListener("click", function() {
    if (confirm("Are you sure you want to cancel this Pomodoro session?")) {
        if (!isPaused && startTime) {
            elapsedTime += (Date.now() - startTime) / 1000;
        }
        if (elapsedTime > 5) {
            const actualDuration = Math.round(elapsedTime);
            window.location.href = `log_session.php?task_id=<?= $task_id ?>&duration=${actualDuration}`;
        } else {
            window.location.href = "index.php";
        }
    }
});
</script>
</body>
</html>
