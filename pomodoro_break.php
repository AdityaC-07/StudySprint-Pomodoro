<?php
// Assuming this break page is linked to the task completion
$task_id = $_GET['task_id'];
$mode = $_GET['mode'] ?? 'standard';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Break Time</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .timer-container {
            text-align: center;
        }
        .clock {
            font-size: 60px;
            font-weight: bold;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            display: inline-block;
        }
        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="timer-container">
        <h1>Break Time!</h1>
        <div id="break-timer" class="clock">
            <span id="break-minutes">05</span>:<span id="break-seconds">00</span>
        </div>
        <a href="index.php" class="btn">Back to Tasks</a>
    </div>

    <script>
        let seconds = 300; // 5 minutes in seconds
        const breakMinutes = document.getElementById("break-minutes");
        const breakSeconds = document.getElementById("break-seconds");

        const interval = setInterval(() => {
            let mins = Math.floor(seconds / 60);
            let secs = seconds % 60;
            breakMinutes.textContent = mins < 10 ? '0' + mins : mins;
            breakSeconds.textContent = secs < 10 ? '0' + secs : secs;

            if (seconds <= 0) {
                clearInterval(interval);
                alert("Break's over! Ready to start the next Pomodoro?");
                window.location.href = "index.php"; // Redirect to home
            }
            seconds--;
        }, 1000);
    </script>

</body>
</html>
