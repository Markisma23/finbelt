<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Allow only admin and super_admin.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$fraudDetector = new FraudDetector();
$fraudFlags = $fraudDetector->getFraudFlags();
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Fraud and Suspicious Activity Report</h2>
    <?php if (count($fraudFlags) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Flag ID</th>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Flag Reason</th>
                    <th>Risk Score</th>
                    <th>Timestamp</th>
                    <th>Reviewed</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fraudFlags as $flag): ?>
                    <tr>
                        <td><?php echo $flag['id']; ?></td>
                        <td><?php echo $flag['user_id']; ?></td>
                        <td><?php echo htmlspecialchars($flag['username']); ?></td>
                        <td><?php echo htmlspecialchars($flag['flag_reason']); ?></td>
                        <td><?php echo $flag['risk_score']; ?></td>
                        <td><?php echo $flag['created_at']; ?></td>
                        <td><?php echo $flag['reviewed'] ? 'Yes' : 'No'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No fraud flags have been generated.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
