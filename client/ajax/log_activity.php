<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

header('Content-Type: application/json');

// Assume the user is logged in; if not, user_id can be null.
$userId = isset($_SESSION['user']) ? $_SESSION['user']['id'] : null;
$eventType = isset($_GET['event_type']) ? trim($_GET['event_type']) : null;
$pageUrl = isset($_GET['page_url']) ? trim($_GET['page_url']) : null;
$additionalData = isset($_GET['data']) ? json_decode($_GET['data'], true) : null;

if (!$eventType) {
    echo json_encode(['status' => 'error', 'message' => 'No event type specified.']);
    exit();
}

$userActivity = new UserActivity();
if ($userActivity->logEvent($userId, $eventType, $pageUrl, $additionalData)) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to log event.']);
}
?>
