<?php
$task_id = $_GET['task_id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pomodoro Timer</title>
</head>
<body>
<h2>⏱️ 25-minute Pomodoro Session</h2>
<p id="timer">25:00</p>
<script>
let seconds = 1500;
const timerDisplay = document.getElementById("timer");
const interval = setInterval(() => {
    let mins = Math.floor(seconds / 60);
    let secs = seconds % 60;
    timerDisplay.textContent = `${mins}:${secs < 10 ? '0' + secs : secs}`;
    if (seconds === 0) {
        clearInterval(interval);
        window.location.href = "log_session.php?task_id=<?= $task_id ?>&duration=1500";
    }
    seconds--;
}, 1000);
</script>
</body>
</html>
