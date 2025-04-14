<?php include 'db.php';

// Get focus data for the last 7 days
$data = [];
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $start = strtotime($date . ' 00:00:00');
    $end = strtotime($date . ' 23:59:59');
    
    $sql = "SELECT SUM(duration) as total FROM history WHERE timestamp BETWEEN $start AND $end";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $minutes = $row['total'] ? round($row['total'] / 60, 1) : 0;
    
    $data[] = [
        'date' => $date,
        'minutes' => $minutes
    ];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Study Progress</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
            background: #f0f4f8;
        }
        .chart-container {
            width: 80%;
            max-width: 800px;
            margin: 20px auto;
            height: 400px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
            padding: 20px;
        }
        .debug-info {
            margin-top: 20px;
            padding: 15px;
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
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
    <h2>📊 Your Focus Time — Last 7 Days</h2>
    
    <div class="chart-container">
        <canvas id="progressChart"></canvas>
    </div>
    
    <!-- Debug information -->
    <div class="debug-info">
        <h3>Data loaded for chart:</h3>
        <pre><?php echo json_encode($data, JSON_PRETTY_PRINT); ?></pre>
    </div>
    
    <div style="text-align: center;">
        <a href="index.php" class="back-btn">⬅ Back to Dashboard</a>
    </div>
    
    <script>
    // Wait for DOM to be fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        try {
            const ctx = document.getElementById('progressChart').getContext('2d');
            
            // Check if Chart.js is loaded
            if (typeof Chart === 'undefined') {
                throw new Error('Chart.js library not loaded');
            }
            
            // Prepare data
            const chartData = {
                labels: <?= json_encode(array_column($data, 'date')) ?>,
                datasets: [{
                    label: 'Minutes Focused',
                    data: <?= json_encode(array_column($data, 'minutes')) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    borderRadius: 6
                }]
            };
            
            // Create chart
            const chart = new Chart(ctx, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.raw + ' minutes';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Minutes'
                            },
                            ticks: { 
                                stepSize: 10 
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        }
                    }
                }
            });
            
            console.log('Chart created successfully');
        } catch (error) {
            console.error('Error creating chart:', error);
            document.querySelector('.chart-container').innerHTML = 
                '<div style="color: red; padding: 20px; text-align: center;">' +
                '<p>Error loading chart: ' + error.message + '</p>' +
                '<p>Please check console for more details.</p>' +
                '</div>';
        }
    });
    </script>
</body>
</html>
