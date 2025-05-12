<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$msgObj = new Message();
$messages = $msgObj->getUserMessages($_SESSION['user']['id']);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Your Messages</h2>
    <?php if(count($messages) > 0): ?>
        <ul>
            <?php foreach($messages as $msg): ?>
            <li>
                <strong><?php echo htmlspecialchars($msg['sender_username']); ?></strong>
                (<?php echo $msg['sent_at']; ?>) - 
                <?php echo htmlspecialchars($msg['message']); ?>
                <?php if (!$msg['is_read']): ?>
                    <span style="color: red;">New</span>
                <?php endif; ?>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No messages found.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
