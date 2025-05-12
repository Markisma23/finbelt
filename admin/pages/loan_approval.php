<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure only admin or super_admin users can access.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'super_admin')) {
    header("Location: login.php");
    exit();
}

$loanApproval = new LoanApproval();
$pendingLoans = $loanApproval->getPendingLoans();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loan_id'], $_POST['action'])) {
    $loanId = intval($_POST['loan_id']);
    $action = $_POST['action']; // Expected values: 'approved', 'rejected', 'on_hold'
    $comment = isset($_POST['comment']) ? trim($_POST['comment']) : null;
    $approverId = $_SESSION['user']['id'];
    
    if ($loanApproval->recordApproval($loanId, $approverId, $action, $comment)) {
        $message = "Loan #{$loanId} has been {$action}.";
        // Refresh pending loans list.
        $pendingLoans = $loanApproval->getPendingLoans();
    } else {
        $message = "Failed to record the approval for Loan #{$loanId}.";
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Loan Approval Workflow</h2>
    <?php if($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if (count($pendingLoans) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Applicant</th>
                    <th>Amount</th>
                    <th>Collateral</th>
                    <th>Applied On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingLoans as $loan): ?>
                    <tr>
                        <td><?php echo $loan['id']; ?></td>
                        <td><?php echo htmlspecialchars($loan['applicant']); ?></td>
                        <td>ZMW <?php echo number_format($loan['amount'],2); ?></td>
                        <td><?php echo htmlspecialchars($loan['collateral']); ?></td>
                        <td><?php echo $loan['applied_at']; ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="loan_id" value="<?php echo $loan['id']; ?>">
                                <select name="action" required>
                                    <option value="">Select Action</option>
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                    <option value="on_hold">Hold</option>
                                </select><br><br>
                                <textarea name="comment" placeholder="Comments (optional)" rows="3" cols="30"></textarea><br>
                                <button type="submit">Submit Decision</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending loan applications.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
