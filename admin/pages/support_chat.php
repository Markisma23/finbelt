<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'support')) {
    header("Location: login.php");
    exit();
}

$customerId = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : 0;
if (!$customerId) {
    echo "No customer selected.";
    exit();
}

$currentUserId = $_SESSION['user']['id']; // Support agent's ID.
$chat = new Chat();

// Process new message submission.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $messageText = trim($_POST['message']);
    if (!empty($messageText)) {
        $chat->sendMessage($currentUserId, $customerId, $messageText);
    }
    header("Location: support_chat.php?customer_id=" . $customerId);
    exit();
}

// Retrieve messages between the support agent and the customer.
$messages = $chat->getMessages($currentUserId, $customerId);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Support Chat with Customer #<?php echo $customerId; ?></h2>
    <div id="admin-chat-box" style="border:1px solid #ccc; padding:10px; height:300px; overflow-y:scroll;">
        <?php if (count($messages) > 0): ?>
            <?php foreach ($messages as $msg): ?>
                <p><strong><?php echo htmlspecialchars($msg['sender_name']); ?>:</strong> <?php echo nl2br(htmlspecialchars($msg['message'])); ?> <small style="color:gray;">(<?php echo $msg['created_at']; ?>)</small></p>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No messages yet.</p>
        <?php endif; ?>
    </div>
    
    <form method="post" action="">
        <textarea name="message" rows="3" style="width:100%;" placeholder="Type your reply..."></textarea>
        <br>
        <button type="submit">Send Reply</button>
    </form>
    
    <!-- Auto-refresh similar to the client chat -->
    <script>
        function refreshAdminChat() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'ajax/get_chat_messages.php?other_user_id=<?php echo $customerId; ?>', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('admin-chat-box').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
        setInterval(refreshAdminChat, 10000);
    </script>
</main>
<?php include '../includes/footer.php'; ?>
