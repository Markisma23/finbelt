<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$loanObj = new Loan();
$userLoans = $loanObj->getUserLoans($_SESSION['user']['id']);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Your Loans Overview</h2>
    <?php if (count($userLoans) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Amount (ZMW)</th>
                    <th>Collateral</th>
                    <th>Status</th>
                    <th>Applied On</th>
                    <th>Repayments</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userLoans as $loan): ?>
                    <tr>
                        <td><?php echo $loan['id']; ?></td>
                        <td><?php echo number_format($loan['amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($loan['collateral']); ?></td>
                        <td><?php echo ucfirst($loan['status']); ?></td>
                        <td><?php echo $loan['applied_at']; ?></td>
                        <td>
                            <a href="repay_loan.php?loan_id=<?php echo $loan['id']; ?>">Manage Repayments</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no active loans.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
