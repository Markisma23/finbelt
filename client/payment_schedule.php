<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}
$user = unserialize($_SESSION['user']);
if ($user->role !== 'client') {
    header("Location: ../public/login.php");
    exit;
}

require_once __DIR__ . '/../includes/classes/Loan.php';

$loanId = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : 0;
$loan = $loanId ? Loan::getLoanById($loanId) : null;

include_once __DIR__ . '/../templates/header.php';
?>
<h2>Payment Schedule</h2>
<?php if ($loan): ?>
    <p><strong>Loan ID:</strong> <?php echo $loan['id']; ?></p>
    <p><strong>Loan Amount:</strong> <?php echo $loan['amount']; ?></p>
    <p><strong>Status:</strong> <?php echo $loan['status']; ?></p>
    <p><strong>Next Due Date:</strong> <?php echo $loan['next_due_date'] ? $loan['next_due_date'] : 'Not set'; ?></p>
    <p>Please ensure your payment is made by the due date to avoid overdue penalties.</p>
<?php else: ?>
    <p>No loan selected. Please provide a valid loan ID in the URL, e.g., <code>?loan_id=1</code>.</p>
<?php endif; ?>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
