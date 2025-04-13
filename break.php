<!DOCTYPE html>
<html>
<head><title>Break Time</title></head>
<body>
<h2>🍵 5-Minute Break</h2>
<p id="break">5:00</p>
<script>
let seconds = 300;
const display = document.getElementById("break");
const interval = setInterval(() => {
    let mins = Math.floor(seconds / 60);
    let secs = seconds % 60;
    display.textContent = `${mins}:${secs < 10 ? '0' + secs : secs}`;
    if (seconds === 0) {
        clearInterval(interval);
        alert("Break over! Ready for the next sprint?");
        window.location.href = "index.php";
    }
    seconds--;
}, 1000);
</script>
</body>
</html>
