<?php
// api/user.php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

header('Content-Type: application/json');

// Check for user authentication.
if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Unauthorized - please log in.']);
    exit();
}

$userData = $_SESSION['user'];

// Respond with user info.
echo json_encode([
    'status' => 'success',
    'data' => $userData
]);
?>
