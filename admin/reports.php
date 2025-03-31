<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}
$user = unserialize($_SESSION['user']);
if ($user->role !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

// Include necessary files
require_once __DIR__ . '/../includes/classes/Database.php';
$pdo = Database::getInstance();

include_once __DIR__ . '/../templates/header.php';

// Query for loan applications report: count loans by status
$stmt1 = $pdo->query("SELECT status, COUNT(*) as count FROM loans GROUP BY status");
$loansReport = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// Query for auctions report: count auctions by category
$stmt2 = $pdo->query("SELECT category, COUNT(*) as count FROM auctions GROUP BY category");
$auctionsReport = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>
<h2>Reports Dashboard</h2>

<div>
    <h3>Loan Applications Report</h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>Status</th>
            <th>Count</th>
        </tr>
        <?php foreach($loansReport as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['status']); ?></td>
            <td><?php echo $row['count']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<div style="margin-top:30px;">
    <h3>Auctions by Category</h3>
    <canvas id="auctionsChart" width="400" height="200"></canvas>
</div>

<!-- Include Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Prepare data for auctions chart
var auctionLabels = [
    <?php
    $labels = [];
    foreach($auctionsReport as $row) {
        $labels[] = '"' . $row['category'] . '"';
    }
    echo implode(", ", $labels);
    ?>
];
var auctionData = [
    <?php
    $data = [];
    foreach($auctionsReport as $row) {
        $data[] = $row['count'];
    }
    echo implode(", ", $data);
    ?>
];

var ctx = document.getElementById('auctionsChart').getContext('2d');
var auctionsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: auctionLabels,
        datasets: [{
            label: 'Auctions Count',
            data: auctionData,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)', 
                'rgba(54, 162, 235, 0.2)', 
                'rgba(255, 206, 86, 0.2)', 
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)'
            ],
            borderColor: [
                'rgba(255,99,132,1)', 
                'rgba(54, 162, 235, 1)', 
                'rgba(255, 206, 86, 1)', 
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 }
            }
        }
    }
});
</script>

<?php include_once __DIR__ . '/../templates/footer.php'; ?>
