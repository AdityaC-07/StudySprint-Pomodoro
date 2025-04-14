<?php
include 'db.php';
session_start();

$task_id = $_GET['task_id'];
$duration = $_GET['duration'];
$mode = $_GET['mode'] ?? 'standard';
$time = time(); // Current Unix timestamp

// Set sessions until long break based on mode
switch ($mode) {
    case 'extended':
        $sessions_until_long_break = 3;
        break;
    case 'quick':
        $sessions_until_long_break = 6;
        break;
    case 'standard':
    default:
        $sessions_until_long_break = 4;
        break;
}

// Store this for future reference
$_SESSION['sessions_until_long_break'] = $sessions_until_long_break;

// Insert the completed pomodoro into history with mode information
$mode_info = $conn->real_escape_string("Mode: $mode");

// Check if notes column exists in history table
$result = $conn->query("SHOW COLUMNS FROM history LIKE 'notes'");
if ($result && $result->num_rows == 0) {
    // Add notes column if it doesn't exist
    $conn->query("ALTER TABLE history ADD COLUMN notes VARCHAR(255)");
}

// Use prepared statement for safer insertion
$stmt = $conn->prepare("INSERT INTO history (task_id, duration, timestamp, notes) VALUES (?, ?, ?, ?)");
if ($stmt) {
    $stmt->bind_param("iiis", $task_id, $duration, $time, $mode_info);
    $stmt->execute();
    $stmt->close();
} else {
    // Fallback to regular query if prepare fails
    $conn->query("INSERT INTO history (task_id, duration, timestamp, notes) 
                 VALUES ('$task_id', '$duration', '$time', '$mode_info')");
}

// Increment pomodoro count
if (!isset($_SESSION['pomodoro_count'])) {
    $_SESSION['pomodoro_count'] = 0;
}
$_SESSION['pomodoro_count']++;

// Determine if it's time for a long break
$breakType = ($_SESSION['pomodoro_count'] % $sessions_until_long_break === 0) ? 'long' : 'short';

// Redirect to appropriate break page
header("Location: pomodoro_break.php?type=$breakType&task_id=$task_id&mode=$mode");
?>
