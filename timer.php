<?php
include 'theme.php'; // Include theme.php at the very top
$task_id = $_GET['task_id'];
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
    stroke: #ccc;
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
    color: <?= ($theme === 'dark') ? '#aaa' : '#666' ?>;
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
            <p id="timer" class="timer-time">25:00</p>
            <p class="timer-label">WORK</p>
        </div>
    </div>
    <button id="controlBtn" class="control-button">⏸</button>
    
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
    
    <a href="index.php">⬅ Back to Tasks</a>
</div>

<script>
const circle = document.getElementById('progress-ring');
const radius = circle.r.baseVal.value;
const circumference = 2 * Math.PI * radius;
circle.style.strokeDasharray = `${circumference} ${circumference}`;
circle.style.strokeDashoffset = 0;

let seconds = 1500;
const totalSeconds = seconds;
let elapsedTime = 0;
let startTime = Date.now();
let isPaused = false;
let intervalId = setInterval(updateTimer, 1000);
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
            icon: "https://example.com/timer-icon.png" // Optional: Add an icon URL
        });
    }
}

// Call showStartNotification when the timer starts
showStartNotification();

// Block notifications while the timer is running
function blockNotifications() {
    if (Notification.permission === "granted") {
        console.log("Notifications are blocked during the timer session.");
    }
}

// Call blockNotifications when the timer starts
blockNotifications();

// Play sound when timer ends
function playTimerCompleteSound() {
    timerSound.currentTime = 0; // Reset to start of audio file
    timerSound.play().catch(error => {
        console.error("Error playing sound:", error);
    });
}

function setProgress(percent) {
    const offset = circumference - (percent / 100 * circumference);
    circle.style.strokeDashoffset = offset;
}

function updateTimerDisplay() {
    let mins = Math.floor(seconds / 60);
    let secs = seconds % 60;
    timerDisplay.textContent = `${mins}:${secs < 10 ? '0' + secs : secs}`;
    const percent = ((totalSeconds - seconds) / totalSeconds) * 100;
    setProgress(percent);
}

function updateTimer() {
    if (seconds > 0) {
        seconds--;
        updateTimerDisplay();
    } else {
        clearInterval(intervalId);
        // Play completion sound before redirecting
        playTimerCompleteSound();
        elapsedTime += (Date.now() - startTime) / 1000;
        const actualDuration = Math.round(elapsedTime);
        
        // Delay redirect to allow sound to play
        setTimeout(() => {
            window.location.href = `log_session.php?task_id=<?= $task_id ?>&duration=${actualDuration}`;
        }, 1500); // Wait 1.5 seconds before redirecting
    }
}

controlBtn.addEventListener("click", function() {
    isPaused = !isPaused;
    if (isPaused) {
        clearInterval(intervalId);
        elapsedTime += (Date.now() - startTime) / 1000;
        this.innerHTML = "▶ ";
    } else {
        this.innerHTML = "⏸";
        startTime = Date.now();
        intervalId = setInterval(updateTimer, 1000);
    }
});

document.querySelector(".close-icon").addEventListener("click", function() {
    if (confirm("Are you sure you want to cancel this Pomodoro session?")) {
        if (!isPaused) {
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
