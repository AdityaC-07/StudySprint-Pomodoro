<?php include 'db.php'; // Get focus data for the last 7 days $data = []; for ($i = 6; $i >= 0; $i--) { $date = date('Y-m-d', strtotime("-$i days")); $start = strtotime($date . ' 00:00:00'); $end = strtotime($date . ' 23:59:59'); $sql = "SELECT SUM(duration) as total FROM history WHERE timestamp BETWEEN $start AND $end"; $result = $conn->query($sql); $row = $result->fetch_assoc(); $minutes = $row['total'] ? round($row['total'] / 60, 1) : 0; $data[] = [ 'date' => $date, 'minutes' => $minutes ]; } ?> <!DOCTYPE html> <html> <head> <title>Study Progress</title> <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <style> body { font-family: sans-serif; padding: 20px; background: #f0f4f8; } canvas { background: #fff; border-radius: 10px; box-shadow: 0 0 10px #ccc; } </style> </head> <body> <h2>📊 Your Focus Time — Last 7 Days</h2> <canvas id="progressChart" width="400" height="200"></canvas>
php-template
Copy
Edit
<script>
    const ctx = document.getElementById('progressChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($data, 'date')) ?>,
            datasets: [{
                label: 'Minutes Focused',
                data: <?= json_encode(array_column($data, 'minutes')) ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                borderRadius: 6
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 10 }
                }
            }
        }
    });
</script>
</body> </html>