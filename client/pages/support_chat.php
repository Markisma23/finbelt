<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// For this example, assume support agent is user ID 1 (adjust as appropriate).
$supportAgentId = 1;
$currentUserId = $_SESSION['user']['id'];
$chat = new Chat();

// Process new message submission.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $messageText = trim($_POST['message']);
    if (!empty($messageText)) {
        $chat->sendMessage($currentUserId, $supportAgentId, $messageText);
    }
    // Redirect to avoid resubmission.
    header("Location: support_chat.php");
    exit();
}

// Retrieve all messages between the current user and the support agent.
$messages = $chat->getMessages($currentUserId, $supportAgentId);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Support Chat</h2>
    <div id="chat-box" style="border:1px solid #ccc; padding:10px; height:300px; overflow-y:scroll;">
        <?php if (count($messages) > 0): ?>
            <?php foreach ($messages as $msg): ?>
                <p><strong><?php echo htmlspecialchars($msg['sender_name']); ?>:</strong> <?php echo nl2br(htmlspecialchars($msg['message'])); ?> <small style="color:gray;">(<?php echo $msg['created_at']; ?>)</small></p>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No messages yet. Start the conversation!</p>
        <?php endif; ?>
    </div>
    
    <form method="post" action="">
        <textarea name="message" rows="3" style="width:100%;" placeholder="Type your message here..."></textarea>
        <br>
        <button type="submit">Send</button>
    </form>
    
    <!-- A JavaScript snippet to auto-refresh the chat box every 10 seconds -->
    <script>
        function refreshChat() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'ajax/get_chat_messages.php?other_user_id=<?php echo $supportAgentId; ?>', true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById('chat-box').innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
        setInterval(refreshChat, 10000); // Refresh every 10 seconds.
    </script>
</main>
<?php include '../includes/footer.php'; ?>
