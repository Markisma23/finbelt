<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Allow access only for admin or super_admin.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT r.*, u.username FROM referrals r JOIN users u ON r.referrer_id = u.id ORDER BY r.created_at DESC");
$referrals = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Referral Program Overview</h2>
    <?php if(count($referrals) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Referral ID</th>
                    <th>Referrer</th>
                    <th>Referral Code</th>
                    <th>Referred Email</th>
                    <th>Status</th>
                    <th>Reward (ZMW)</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($referrals as $ref): ?>
                    <tr>
                        <td><?php echo $ref['id']; ?></td>
                        <td><?php echo htmlspecialchars($ref['username']); ?></td>
                        <td><?php echo htmlspecialchars($ref['referral_code']); ?></td>
                        <td><?php echo htmlspecialchars($ref['referred_email']); ?></td>
                        <td><?php echo ucfirst($ref['status']); ?></td>
                        <td>ZMW <?php echo number_format($ref['reward'], 2); ?></td>
                        <td><?php echo $ref['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No referral records available.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
