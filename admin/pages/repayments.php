<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure only admins can access this page.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$db = Database::getInstance()->getConnection();
$stmt = $db->query("
    SELECT r.id, r.loan_id, r.amount, r.paid_at, l.user_id, u.username 
    FROM repayments r
    JOIN loans l ON r.loan_id = l.id
    JOIN users u ON l.user_id = u.id
    ORDER BY r.paid_at DESC
");
$repaymentList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Repayments Overview</h2>
    <?php if (count($repaymentList) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Repayment ID</th>
                    <th>Loan ID</th>
                    <th>User</th>
                    <th>Amount (ZMW)</th>
                    <th>Paid At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($repaymentList as $repayment): ?>
                    <tr>
                        <td><?php echo $repayment['id']; ?></td>
                        <td><?php echo $repayment['loan_id']; ?></td>
                        <td><?php echo htmlspecialchars($repayment['username']); ?></td>
                        <td><?php echo number_format($repayment['amount'], 2); ?></td>
                        <td><?php echo $repayment['paid_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No repayments found.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
