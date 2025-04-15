const circle = document.getElementById('progress-ring');
const radius = circle.r.baseVal.value;
const circumference = 2 * Math.PI * radius;
circle.style.strokeDasharray = `${circumference} ${circumference}`;
circle.style.strokeDashoffset = 0;

let seconds = 1500;
const totalSeconds = seconds;
let isPaused = false;
let intervalId;
const timerDisplay = document.getElementById("timer");
const controlBtn = document.getElementById("controlBtn");
const timerSound = document.getElementById("timerSound");
const volumeControl = document.getElementById("volume");

volumeControl.addEventListener("input", function() {
  timerSound.volume = this.value;
});

if (Notification.permission !== "granted") {
  Notification.requestPermission();
}

function showNotification(message) {
  if (Notification.permission === "granted") {
    new Notification("Pomodoro Timer", { body: message });
  }
}

function playTimerCompleteSound() {
  timerSound.currentTime = 0;
  timerSound.play().catch(error => console.error("Error playing sound:", error));
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
    playTimerCompleteSound();
    showNotification("Time's up! Take a break.");
  }
}

controlBtn.addEventListener("click", function() {
  isPaused = !isPaused;
  if (isPaused) {
    clearInterval(intervalId);
    this.textContent = "▶";
  } else {
    this.textContent = "⏸";
    intervalId = setInterval(updateTimer, 1000);
  }
});

intervalId = setInterval(updateTimer, 1000);