<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure that only admin users may access this page.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$report = new Report();

$totalLoans       = $report->getTotalLoans();
$sumOfLoans       = $report->getSumOfLoans();
$totalRepayments  = $report->getTotalRepayments();
$totalRepayCount  = $report->getTotalRepaymentsCount();
$activeAuctions   = $report->getActiveAuctionsCount();
$outstandingBalance = $report->getOutstandingLoanBalance();

$kycSummary = $report->getKycSummary();
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Admin Reports Dashboard</h2>
    
    <section>
        <h3>Loan Summary</h3>
        <ul>
            <li>Total Loans Applied: <strong><?php echo $totalLoans; ?></strong></li>
            <li>Sum of Loan Amounts: <strong>ZMW <?php echo number_format($sumOfLoans, 2); ?></strong></li>
            <li>Outstanding Loan Balance: <strong>ZMW <?php echo number_format($outstandingBalance, 2); ?></strong></li>
        </ul>
    </section>
    
    <section>
        <h3>Repayment Summary</h3>
        <ul>
            <li>Total Repayments Made: <strong><?php echo $totalRepayCount; ?></strong></li>
            <li>Sum of Repayments: <strong>ZMW <?php echo number_format($totalRepayments, 2); ?></strong></li>
        </ul>
    </section>
    
    <section>
        <h3>Auction Summary</h3>
        <ul>
            <li>Active Auctions: <strong><?php echo $activeAuctions; ?></strong></li>
        </ul>
    </section>
    
    <section>
        <h3>KYC Document Summary</h3>
        <?php if (!empty($kycSummary)): ?>
            <ul>
                <?php foreach ($kycSummary as $status => $count): ?>
                    <li><?php echo ucfirst($status); ?>: <strong><?php echo $count; ?></strong></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No KYC documents have been processed yet.</p>
        <?php endif; ?>
    </section>
    
    <!-- Placeholder for additional charts/visualizations -->
    <!--
    <section>
        <h3>Visual Reports</h3>
        <div id="chartContainer">[Chart Placeholder]</div>
    </section>
    -->
</main>
<?php include '../includes/footer.php'; ?>
