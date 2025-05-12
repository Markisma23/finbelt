<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure only admins or super_admin users have access.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT l.*, u.username FROM loans l JOIN users u ON l.user_id = u.id WHERE l.status = 'defaulted' ORDER BY l.applied_at DESC");
$defaultedLoans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Defaulted Loans</h2>
    <?php if (count($defaultedLoans) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Borrower</th>
                    <th>Loan Amount (ZMW)</th>
                    <th>Collateral</th>
                    <th>Applied On</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($defaultedLoans as $loan): ?>
                    <tr>
                        <td><?php echo $loan['id']; ?></td>
                        <td><?php echo htmlspecialchars($loan['username']); ?></td>
                        <td><?php echo number_format($loan['amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($loan['collateral']); ?></td>
                        <td><?php echo $loan['applied_at']; ?></td>
                        <td><?php echo ucfirst($loan['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No defaulted loans at this time.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
