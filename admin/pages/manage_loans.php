<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Only allow logged-in admins.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$loanObj = new Loan();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['loan_id'], $_POST['status'])) {
    $loanId = intval($_POST['loan_id']);
    $status = trim($_POST['status']);
    
    if ($loanObj->updateLoanStatus($loanId, $status)) {
        $message = "Loan #$loanId has been updated to status: " . ucfirst($status);
    } else {
        $message = "Failed to update loan status for loan #$loanId.";
    }
}

$allLoans = $loanObj->getAllLoans();
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Manage Loan Applications</h2>
    <?php if($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if (count($allLoans) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Username</th>
                    <th>Amount (ZMW)</th>
                    <th>Collateral</th>
                    <th>Status</th>
                    <th>Applied On</th>
                    <th>Update Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($allLoans as $loan): ?>
                    <tr>
                        <td><?php echo $loan['id']; ?></td>
                        <td><?php echo htmlspecialchars($loan['username']); ?></td>
                        <td><?php echo number_format($loan['amount'],2); ?></td>
                        <td><?php echo htmlspecialchars($loan['collateral']); ?></td>
                        <td><?php echo ucfirst($loan['status']); ?></td>
                        <td><?php echo $loan['applied_at']; ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="loan_id" value="<?php echo $loan['id']; ?>">
                                <select name="status" required>
                                    <option value="">Select</option>
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                    <option value="defaulted">Mark as Defaulted</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No loan applications found.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
