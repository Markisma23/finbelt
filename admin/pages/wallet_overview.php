<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure admin access.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$db = Database::getInstance()->getConnection();
$stmt = $db->query("
    SELECT w.*, u.username, u.email 
    FROM wallets w 
    JOIN users u ON w.user_id = u.id 
    ORDER BY w.balance DESC
");
$wallets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>User Wallet Overview</h2>
    <?php if (count($wallets) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Balance (ZMW)</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($wallets as $wallet): ?>
                    <tr>
                        <td><?php echo $wallet['user_id']; ?></td>
                        <td><?php echo htmlspecialchars($wallet['username']); ?></td>
                        <td><?php echo htmlspecialchars($wallet['email']); ?></td>
                        <td>ZMW <?php echo number_format($wallet['balance'], 2); ?></td>
                        <td><?php echo $wallet['updated_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No wallet data available.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
