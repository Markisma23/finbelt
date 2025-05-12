<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure only admins can access this page.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$audit = new AuditLog();
$logs = $audit->getLogs(); // You can extend this to use filters via GET parameters.
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Audit Logs</h2>
    <?php if (count($logs) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>User</th>
                    <th>Event Type</th>
                    <th>Description</th>
                    <th>IP Address</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo $log['id']; ?></td>
                        <td><?php echo $log['username'] ? htmlspecialchars($log['username']) : 'System'; ?></td>
                        <td><?php echo htmlspecialchars($log['event_type']); ?></td>
                        <td><?php echo htmlspecialchars($log['description']); ?></td>
                        <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                        <td><?php echo $log['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No audit logs found.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
