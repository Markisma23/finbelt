<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$user = unserialize($_SESSION['user']);
if ($user->role !== 'client') {
    echo json_encode(['error' => 'Invalid user role']);
    exit;
}

require_once __DIR__ . '/../includes/classes/Notification.php';

// Get notification message for preferred auctions
$notification = Notification::notifyPreferredAuctions($user->id);
echo json_encode(['notification' => $notification]);
