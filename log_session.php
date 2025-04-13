<?php
include 'db.php';
$task_id = $_GET['task_id'];
$duration = $_GET['duration'];
$time = time();
$conn->query("INSERT INTO history (task_id, duration, timestamp) VALUES ($task_id, $duration, $time)");
header("Location: break.php");
?>
