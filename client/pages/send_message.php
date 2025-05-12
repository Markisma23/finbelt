<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$msgMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['receiver_id'], $_POST['message_text'])) {
    $receiverId = intval($_POST['receiver_id']);
    $messageText = trim($_POST['message_text']);
    $msgObj = new Message();
    if ($msgObj->sendMessage($_SESSION['user']['id'], $receiverId, $messageText)) {
        $msgMessage = "Message sent successfully!";
    } else {
        $msgMessage = "Failed to send message.";
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Send Message</h2>
    <?php if($msgMessage): ?>
        <p><?php echo htmlspecialchars($msgMessage); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="receiver_id">Receiver User ID:</label>
        <input type="number" id="receiver_id" name="receiver_id" required>
        
        <label for="message_text">Message:</label>
        <textarea id="message_text" name="message_text" required></textarea>
        
        <button type="submit">Send Message</button>
    </form>
</main>
<?php include '../includes/footer.php'; ?>
