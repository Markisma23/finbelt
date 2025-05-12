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

$loanApproval = new LoanApproval();
$approvalHistory = $loanApproval->getApprovalHistory($loanId);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Approval History for Loan #<?php echo $loanId; ?></h2>
    <?php if (count($approvalHistory) > 0): ?>
        <ul>
            <?php foreach ($approvalHistory as $record): ?>
                <li>
                    [<?php echo $record['created_at']; ?>] <?php echo htmlspecialchars($record['approver_name']); ?> - 
                    <?php echo ucfirst($record['status']); ?> <?php echo $record['comment'] ? "- " . htmlspecialchars($record['comment']) : ""; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No approval history available for this loan.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
