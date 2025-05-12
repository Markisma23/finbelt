<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$notify = new Notification();
$notifications = $notify->getNotifications($_SESSION['user']['id']);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Notifications</h2>
    <?php if (count($notifications) > 0): ?>
        <ul>
            <?php foreach($notifications as $n): ?>
                <li><?php echo htmlspecialchars($n['message']); ?> 
                    (<?php echo $n['created_at']; ?>)
                    <?php if (!$n['is_read']): ?> <strong>New</strong> <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No notifications yet.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
