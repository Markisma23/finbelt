<?php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$notification = new Notification();
$notifications = $notification->getNotifications($_SESSION['user']['id']);

echo json_encode($notifications);
