<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

header('Content-Type: text/html');

if (!isset($_SESSION['user'])) {
    echo "Unauthorized.";
    exit();
}

$currentUserId = $_SESSION['user']['id'];
$otherUserId = isset($_GET['other_user_id']) ? intval($_GET['other_user_id']) : 0;
if (!$otherUserId) {
    echo "Invalid conversation.";
    exit();
}

$chat = new Chat();
$messages = $chat->getMessages($currentUserId, $otherUserId);
foreach ($messages as $msg) {
    echo '<p><strong>' . htmlspecialchars($msg['sender_name']) . ':</strong> ' . nl2br(htmlspecialchars($msg['message'])) . ' <small style="color:gray;">(' . $msg['created_at'] . ')</small></p>';
}
?>
