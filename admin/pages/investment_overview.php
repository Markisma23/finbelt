<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure admin or super_admin access.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$db = Database::getInstance()->getConnection();
$stmt = $db->query("
    SELECT i.*, u.username as investor, l.amount as loan_amount, l.collateral, l.applied_at
    FROM investments i
    JOIN users u ON i.investor_id = u.id
    JOIN loans l ON i.loan_id = l.id
    ORDER BY i.invested_at DESC
");
$investments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Investment Overview</h2>
    <?php if (count($investments) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Investment ID</th>
                    <th>Investor</th>
                    <th>Loan ID</th>
                    <th>Invested Amount (ZMW)</th>
                    <th>Loan Amount (ZMW)</th>
                    <th>Collateral</th>
                    <th>Invested At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($investments as $inv): ?>
                    <tr>
                        <td><?php echo $inv['id']; ?></td>
                        <td><?php echo htmlspecialchars($inv['investor']); ?></td>
                        <td><?php echo $inv['loan_id']; ?></td>
                        <td>ZMW <?php echo number_format($inv['amount'], 2); ?></td>
                        <td>ZMW <?php echo number_format($inv['loan_amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($inv['collateral']); ?></td>
                        <td><?php echo $inv['invested_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No investments recorded.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
