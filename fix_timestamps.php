<?php
// fix_timestamps.php - Run this once to fix existing timestamp issues
include 'db.php';

// Get all history records
$result = $conn->query("SELECT * FROM history");
$fixed = 0;

while ($row = $result->fetch_assoc()) {
    $timestamp = (int)$row['timestamp'];
    $id = $row['id'];
    
    // Check if timestamp is unreasonable (future date or Year 2038 problem)
    if ($timestamp <= 0 || $timestamp > time() || $timestamp > 2145916800) {
        // 2145916800 is roughly Jan 1, 2038
        
        // Set to current time
        $new_timestamp = time();
        $conn->query("UPDATE history SET timestamp = $new_timestamp WHERE id = $id");
        $fixed++;
    }
}

echo "Fixed $fixed timestamp records.";
?>
