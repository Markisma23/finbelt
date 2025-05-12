<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get the loan ID from GET parameter.
$loanId = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : 0;
if (!$loanId) {
    echo "Invalid loan specified.";
    exit();
}

$repaymentSchedule = new RepaymentSchedule();
$schedule = $repaymentSchedule->getSchedule($loanId);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Repayment Schedule for Loan #<?php echo $loanId; ?></h2>
    <?php if (count($schedule) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Installment</th>
                    <th>Due Date</th>
                    <th>Principal Due (ZMW)</th>
                    <th>Interest Due (ZMW)</th>
                    <th>Total Due (ZMW)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedule as $installment): ?>
                    <tr>
                        <td><?php echo $installment['installment_number']; ?></td>
                        <td><?php echo $installment['due_date']; ?></td>
                        <td><?php echo number_format($installment['principal_due'],2); ?></td>
                        <td><?php echo number_format($installment['interest_due'],2); ?></td>
                        <td><?php echo number_format($installment['total_due'],2); ?></td>
                        <td><?php echo ucfirst($installment['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No repayment schedule available. It might not have been generated yet.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
