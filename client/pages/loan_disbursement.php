<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$loanId = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : 0;
if (!$loanId) {
    echo "Invalid loan specified.";
    exit();
}

$disbursementObj = new AutomatedDisbursement();
$disbursement = $disbursementObj->getDisbursement($loanId);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Loan Disbursement Status for Loan #<?php echo $loanId; ?></h2>
    <?php if ($disbursement): ?>
        <p><strong>Amount:</strong> ZMW <?php echo number_format($disbursement['disbursement_amount'], 2); ?></p>
        <p><strong>Status:</strong> <?php echo ucfirst($disbursement['status']); ?></p>
        <?php if ($disbursement['status'] == 'disbursed'): ?>
            <p><strong>Disbursed At:</strong> <?php echo $disbursement['disbursed_at']; ?></p>
        <?php endif; ?>
    <?php else: ?>
        <p>Disbursement not yet scheduled or processed.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
