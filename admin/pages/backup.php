<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Only super admin has access
if ($_SESSION['user']['role'] !== 'super_admin') {
    echo "Access denied: Insufficient permissions.";
    exit();
}

$message = '';
if (isset($_GET['backup'])) {
    $admin = new Admin();
    if ($admin->backupDatabase()) {
        $message = "Database backup successful.";
    } else {
        $message = "Failed to backup the database.";
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Database Backup</h2>
    <?php if($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <p>Click below to initiate a database backup.</p>
    <a href="?backup=1">Backup Now</a>
</main>
<?php include '../includes/footer.php'; ?>
