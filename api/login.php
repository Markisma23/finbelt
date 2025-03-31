<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/classes/User.php';
require_once __DIR__ . '/api_util.php';
require_once __DIR__ . '/../includes/classes/Logger.php';

$logger = new Logger();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    $logger->log("API Login attempted with invalid method: " . $_SERVER['REQUEST_METHOD'], 'WARNING');
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
if (!isset($input['email']) || !isset($input['password'])) {
    http_response_code(400);
    $logger->log("API Login failed: missing email or password", 'ERROR');
    echo json_encode(['status' => 'error', 'message' => 'Email and password required']);
    exit;
}

$user = User::login($input['email'], $input['password']);
if (!$user) {
    http_response_code(401);
    $logger->log("API Login failed for email: " . $input['email'], 'ERROR');
    echo json_encode(['status' => 'error', 'message' => 'Invalid credentials']);
    exit;
}

// Generate a token for the user
$token = generateToken($user->id);
$logger->log("API Login successful for user id " . $user->id, 'INFO');
echo json_encode(['status' => 'success', 'token' => $token]);
