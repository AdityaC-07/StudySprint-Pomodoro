<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Task Statistics</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background: #f0f4f8;
        }
        .stats-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            flex: 1;
            min-width: 200px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
            color: #007bff;
        }
        .chart-container {
            width: 100%;
            max-width: 800px;
            margin: 20px auto;
            height: 400px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h2>📊 Task Statistics</h2>
    
    <?php
    // Calculate total focus time
    $totalResult = $conn->query("SELECT SUM(duration) as total FROM history");
    $totalMinutes = round($totalResult->fetch_assoc()['total'] / 60, 1);
    
    // Calculate total tasks
    $tasksResult = $conn->query("SELECT COUNT(*) as count FROM tasks");
    $totalTasks = $tasksResult->fetch_assoc()['count'];
    
    // Calculate completed tasks
    $completedResult = $conn->query("SELECT COUNT(DISTINCT task_id) as count FROM history");
    $completedTasks = $completedResult->fetch_assoc()['count'];
    
    // Calculate average session length
    $avgResult = $conn->query("SELECT AVG(duration) as average FROM history");
    $avgMinutes = round($avgResult->fetch_assoc()['average'] / 60, 1);
    
    // Get task time distribution
    $taskTimeQuery = $conn->query("
        SELECT t.title, SUM(h.duration) as total_time 
        FROM history h
        JOIN tasks t ON h.task_id = t.id
        GROUP BY h.task_id
        ORDER BY total_time DESC
        LIMIT 5
    ");
    
    $taskLabels = [];
    $taskTimes = [];
    
    while ($row = $taskTimeQuery->fetch_assoc()) {
        $taskLabels[] = $row['title'];
        $taskTimes[] = round($row['total_time'] / 60, 1);
    }
    
    // Get mode distribution
    $modeQuery = $conn->query("
        SELECT 
            SUBSTRING_INDEX(SUBSTRING_INDEX(notes, 'Mode: ', -1), ' ', 1) as mode,
            COUNT(*) as count
        FROM history
        WHERE notes LIKE 'Mode:%'
        GROUP BY mode
    ");
    
    $modeLabels = [];
    $modeCounts = [];
    
    while ($row = $modeQuery->fetch_assoc()) {
        $modeLabels[] = ucfirst($row['mode']);
        $modeCounts[] = $row['count'];
    }
    ?>
    
    <div class="stats-container">
        <div class="stat-card">
            <h3>Total Focus Time</h3>
            <div class="stat-value"><?php echo $totalMinutes; ?> minutes</div>
        </div>
        
        <div class="stat-card">
            <h3>Total Tasks</h3>
            <div class="stat-value"><?php echo $totalTasks; ?></div>
        </div>
        
        <div class="stat-card">
            <h3>Tasks Worked On</h3>
            <div class="stat-value"><?php echo $completedTasks; ?></div>
        </div>
        
        <div class="stat-card">
            <h3>Avg Session Length</h3>
            <div class="stat-value"><?php echo $avgMinutes; ?> minutes</div>
        </div>
    </div>
    
    <div class="chart-container">
        <h3>Time Spent per Task</h3>
        <canvas id="taskTimeChart"></canvas>
    </div>
    
    <div class="chart-container">
        <h3>Sessions by Mode</h3>
        <canvas id="modeChart"></canvas>
    </div>
    
    <div style="text-align: center;">
        <a href="index.php" class="back-btn">⬅ Back to Dashboard</a>
    </div>
    
    <script>
    // Task time chart
    const taskTimeCtx = document.getElementById('taskTimeChart').getContext('2d');
    new Chart(taskTimeCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($taskLabels); ?>,
            datasets: [{
                label: 'Minutes Spent',
                data: <?php echo json_encode($taskTimes); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
    
    // Mode distribution chart
    const modeCtx = document.getElementById('modeChart').getContext('2d');
    new Chart(modeCtx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($modeLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($modeCounts); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
        }
    });
    </script>
</body>
</html>