<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}
$user = unserialize($_SESSION['user']);
if ($user->role !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}
require_once __DIR__ . '/../includes/classes/Loan.php';
include_once __DIR__ . '/../templates/header.php';

// Fetch all loans
$loans = Loan::getAllLoans();
?>
<h2>Manage Loan Applications</h2>
<?php if ($loans): ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Client ID</th>
            <th>Amount</th>
            <th>Collateral</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($loans as $loan): ?>
        <tr>
            <td><?php echo $loan['id']; ?></td>
            <td><?php echo $loan['client_id']; ?></td>
            <td><?php echo $loan['amount']; ?></td>
            <td><?php echo htmlspecialchars($loan['collateral']); ?></td>
            <td><?php echo $loan['status']; ?></td>
            <td>
                <?php if ($loan['status'] === 'pending'): ?>
                    <form method="post" action="approve_loan.php">
                        <input type="hidden" name="loan_id" value="<?php echo $loan['id']; ?>" />
                        <button type="submit">Approve</button>
                    </form>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No loan applications found.</p>
<?php endif; ?>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
