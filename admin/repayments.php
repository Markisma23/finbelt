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

require_once __DIR__ . '/../includes/classes/LoanRepayment.php';
$pdo = \finbelt\loans\Database::getInstance(); // Or simply use Database::getInstance() if in global namespace

// Get all repayment records (for demonstration, a simple query)
$stmt = $pdo->query("SELECT lr.*, u.name AS client_name, l.amount AS loan_amount
                     FROM loan_repayments lr
                     JOIN users u ON lr.client_id = u.id
                     JOIN loans l ON lr.loan_id = l.id
                     ORDER BY lr.payment_date DESC");
$repayments = $stmt->fetchAll(PDO::FETCH_ASSOC);

include_once __DIR__ . '/../templates/header.php';
?>
<h2>All Loan Repayments</h2>
<?php if ($repayments): ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>Repayment ID</th>
            <th>Loan ID</th>
            <th>Client</th>
            <th>Payment Amount</th>
            <th>Remaining Balance</th>
            <th>Payment Date</th>
        </tr>
        <?php foreach ($repayments as $repayment): ?>
        <tr>
            <td><?php echo $repayment['id']; ?></td>
            <td><?php echo $repayment['loan_id']; ?></td>
            <td><?php echo htmlspecialchars($repayment['client_name']); ?></td>
            <td><?php echo $repayment['payment_amount']; ?></td>
            <td><?php echo $repayment['remaining_balance']; ?></td>
            <td><?php echo $repayment['payment_date']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No repayment records found.</p>
<?php endif; ?>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
