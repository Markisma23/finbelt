<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Only allow access for admin and super_admin users.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$reportObj = new RegulatoryReport();

// Determine the date range (defaults to yesterday if not provided).
if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $startDate = $_GET['start_date'];
    $endDate = $_GET['end_date'];
} else {
    $startDate = date('Y-m-d', strtotime('-1 day'));
    $endDate = date('Y-m-d');
}

$report = $reportObj->generateReport($startDate, $endDate);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Regulatory Report</h2>
    <form method="get" action="">
        <label for="start_date">Start Date: </label>
        <input type="date" name="start_date" id="start_date" value="<?php echo htmlspecialchars($startDate); ?>" required>
        <label for="end_date">End Date: </label>
        <input type="date" name="end_date" id="end_date" value="<?php echo htmlspecialchars($endDate); ?>" required>
        <button type="submit">Generate Report</button>
    </form>
    <h3>Report from <?php echo htmlspecialchars($startDate); ?> to <?php echo htmlspecialchars($endDate); ?></h3>
    <table border="1" cellpadding="5">
        <tr>
            <th>Total Loans Applied</th>
            <td><?php echo htmlspecialchars($report['loans']['total_loans']); ?></td>
        </tr>
        <tr>
            <th>Total Loan Amount</th>
            <td>ZMW <?php echo number_format($report['loans']['total_amount'], 2); ?></td>
        </tr>
        <tr>
            <th>Average Loan Amount</th>
            <td>ZMW <?php echo number_format($report['avg_loan'], 2); ?></td>
        </tr>
        <tr>
            <th>Total Repayments Made</th>
            <td><?php echo htmlspecialchars($report['repayments']['total_repayments']); ?></td>
        </tr>
        <tr>
            <th>Total Amount Repaid</th>
            <td>ZMW <?php echo number_format($report['repayments']['total_repaid'], 2); ?></td>
        </tr>
        <tr>
            <th>Total Defaults</th>
            <td><?php echo htmlspecialchars($report['defaults']); ?></td>
        </tr>
        <tr>
            <th>Active Auctions</th>
            <td><?php echo htmlspecialchars($report['active_auctions']); ?></td>
        </tr>
    </table>
    <br>
    <p>
        <!-- Link to Data Export module (if integrated) for regulatory data export -->
        <a href="../pages/export_data.php?export=regulatory&start_date=<?php echo urlencode($startDate); ?>&end_date=<?php echo urlencode($endDate); ?>">Export Report as CSV</a>
    </p>
</main>
<?php include '../includes/footer.php'; ?>
