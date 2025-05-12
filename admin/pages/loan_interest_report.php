<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure only admin users have access.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT * FROM loans");
$allLoans = $stmt->fetchAll(PDO::FETCH_ASSOC);
$interestCalc = new InterestCalculator();
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Loan Interest Report</h2>
    <?php if (count($allLoans) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>User ID</th>
                    <th>Loan Amount (ZMW)</th>
                    <th>Applied On</th>
                    <th>Outstanding Principal (ZMW)</th>
                    <th>Accrued Interest (ZMW)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allLoans as $loan): 
                    $outstanding = $interestCalc->getOutstandingPrincipal($loan['id']);
                    $interest = $interestCalc->getInterestAccrued($loan['id']);
                ?>
                    <tr>
                        <td><?php echo $loan['id']; ?></td>
                        <td><?php echo $loan['user_id']; ?></td>
                        <td><?php echo number_format($loan['amount'], 2); ?></td>
                        <td><?php echo $loan['applied_at']; ?></td>
                        <td><?php echo number_format($outstanding, 2); ?></td>
                        <td><?php echo number_format($interest, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No loan data available.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
