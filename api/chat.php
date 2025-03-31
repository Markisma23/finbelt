<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/classes/Chat.php';
require_once __DIR__ . '/api_util.php';

// Require a valid token; this gives us the authenticated user.
$user = requireToken();

// Handle GET to retrieve messages and POST to send a message.
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Expect a query parameter "with" that specifies the other user's ID.
    if (!isset($_GET['with'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Parameter "with" is required']);
        exit;
    }
    $otherUserId = intval($_GET['with']);
    $messages = Chat::getMessagesBetween($user->id, $otherUserId);
    echo json_encode(['status' => 'success', 'data' => $messages]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    if (!isset($input['recipient_id'], $input['message'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'recipient_id and message are required']);
        exit;
    }
    $recipientId = intval($input['recipient_id']);
    $message = trim($input['message']);
    if (Chat::sendMessage($user->id, $recipientId, $message)) {
        echo json_encode(['status' => 'success', 'message' => 'Message sent']);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Failed to send message']);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
