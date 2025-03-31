<?php
header('Content-Type: application/json');
require_once __DIR__ . '/api_util.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Extract current token from header
$currentToken = getAuthToken();
if (!$currentToken) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Authentication token required']);
    exit;
}

// Validate the current token
$user = validateToken($currentToken);
if (!$user) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token']);
    exit;
}

// Generate a new token for the user
$newToken = generateToken($user->id);

// Optionally, you can remove the old token from the database here
$pdo = Database::getInstance();
$stmt = $pdo->prepare("DELETE FROM api_tokens WHERE token = :token");
$stmt->execute(['token' => $currentToken]);

// Return the new token
echo json_encode(['status' => 'success', 'token' => $newToken]);
