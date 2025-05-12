<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$loanObj = new Loan();
$loans = $loanObj->getUserLoans($_SESSION['user']['id']);
$interestCalc = new InterestCalculator();
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Your Loan Interest Overview</h2>
    <?php if (count($loans) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Loan Amount (ZMW)</th>
                    <th>Status</th>
                    <th>Applied On</th>
                    <th>Outstanding Principal (ZMW)</th>
                    <th>Accrued Interest (ZMW)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($loans as $loan): 
                    $outstanding = $interestCalc->getOutstandingPrincipal($loan['id']);
                    $interest = $interestCalc->getInterestAccrued($loan['id']);
                ?>
                    <tr>
                        <td><?php echo $loan['id']; ?></td>
                        <td><?php echo number_format($loan['amount'], 2); ?></td>
                        <td><?php echo ucfirst($loan['status']); ?></td>
                        <td><?php echo $loan['applied_at']; ?></td>
                        <td><?php echo number_format($outstanding, 2); ?></td>
                        <td><?php echo number_format($interest, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no active loans at this time.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
