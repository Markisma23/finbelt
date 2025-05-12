<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$ticketId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$ticketId) {
    echo "Invalid ticket specified.";
    exit();
}

$ticketObj = new SupportTicket();
$ticket = $ticketObj->getTicket($ticketId);

if (!$ticket || $ticket['user_id'] != $_SESSION['user']['id']) {
    echo "Ticket not found or access denied.";
    exit();
}

$messageFeedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply_message'])) {
    $replyMessage = trim($_POST['reply_message']);
    if ($ticketObj->addReply($ticketId, $_SESSION['user']['id'], $replyMessage)) {
        $messageFeedback = "Reply added successfully.";
    } else {
        $messageFeedback = "Failed to add reply.";
    }
}

$replies = $ticketObj->getReplies($ticketId);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Ticket #<?php echo $ticket['id']; ?> - <?php echo htmlspecialchars($ticket['subject']); ?></h2>
    <p><strong>Status:</strong> <?php echo ucfirst($ticket['status']); ?></p>
    <p><strong>Message:</strong> <?php echo nl2br(htmlspecialchars($ticket['message'])); ?></p>
    <hr>
    <h3>Conversation</h3>
    <?php if (count($replies) > 0): ?>
        <?php foreach ($replies as $reply): ?>
            <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
                <p><strong><?php echo htmlspecialchars($reply['username']); ?></strong> (<?php echo $reply['created_at']; ?>):</p>
                <p><?php echo nl2br(htmlspecialchars($reply['message'])); ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No replies yet.</p>
    <?php endif; ?>
    
    <h3>Add Your Reply</h3>
    <?php if ($messageFeedback): ?>
        <p style="color:green;"><?php echo htmlspecialchars($messageFeedback); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <textarea name="reply_message" rows="4" cols="50" required></textarea><br><br>
        <button type="submit">Send Reply</button>
    </form>
</main>
<?php include '../includes/footer.php'; ?>
