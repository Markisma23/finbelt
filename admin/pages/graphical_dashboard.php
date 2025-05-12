<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure that only admin or super_admin users have access.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'super_admin')) {
    header("Location: login.php");
    exit();
}

$report = new Report();
$kycSummary = $report->getKycSummary();

// Prepare data for the KYC pie chart.
$labels = [];
$data = [];
foreach ($kycSummary as $status => $count) {
    $labels[] = ucfirst($status);
    $data[] = $count;
}

// For demonstration purposes, we'll create dummy data for monthly loans.
// In a real production system, you would retrieve this data via a database query.
$monthlyLoanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$monthlyLoanData = [5, 10, 15, 7, 12, 9, 20, 25, 18, 30, 22, 16];  // Dummy data for illustration.
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Graphical Dashboard</h2>
    
    <!-- KYC Document Summary Chart -->
    <section>
        <h3>KYC Document Summary</h3>
        <canvas id="kycChart" width="400" height="400"></canvas>
    </section>
    
    <!-- Monthly Loan Applications Chart -->
    <section>
        <h3>Monthly Loan Applications</h3>
        <canvas id="loanChart" width="600" height="400"></canvas>
    </section>
    
    <!-- Include Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // KYC Document Summary Pie Chart Configuration
        const ctxKYC = document.getElementById('kycChart').getContext('2d');
        const kycChart = new Chart(ctxKYC, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'KYC Documents',
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
        
        // Monthly Loan Applications Bar Chart Configuration
        const ctxLoan = document.getElementById('loanChart').getContext('2d');
        const loanChart = new Chart(ctxLoan, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($monthlyLoanLabels); ?>,
                datasets: [{
                    label: 'Number of Loans',
                    data: <?php echo json_encode($monthlyLoanData); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
</main>
<?php include '../includes/footer.php'; ?>
