<?php
require_once __DIR__ . '/../includes/classes/Database.php';
require_once __DIR__ . '/../includes/classes/User.php';

/**
 * Extract the token from the Authorization header.
 */
function getAuthToken() {
    $headers = getallheaders();
    if (!isset($headers['Authorization'])) {
        return null;
    }
    $authHeader = $headers['Authorization'];
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        return $matches[1];
    }
    return null;
}

/**
 * Validate the provided token.
 * Returns the associated user if valid, or false if not.
 */
function validateToken($token) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM api_tokens WHERE token = :token AND expires_at > NOW()");
    $stmt->execute(['token' => $token]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($record) {
        // Get the user record from the users table
        $stmtUser = $pdo->prepare("SELECT * FROM users WHERE id = :user_id");
        $stmtUser->execute(['user_id' => $record['user_id']]);
        $user = $stmtUser->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            // Instantiate the proper user object (admin/client)
            if ($user['role'] === 'admin') {
                require_once __DIR__ . '/../includes/classes/Admin.php';
                return new Admin($user['id'], $user['name'], $user['email'], $user['password'], $user['permissions']);
            } else {
                require_once __DIR__ . '/../includes/classes/Client.php';
                return new Client($user['id'], $user['name'], $user['email'], $user['password']);
            }
        }
    }
    return false;
}

/**
 * Generate a new token for a user and store it in the database.
 * Tokens are valid for a fixed period (e.g., 1 day).
 */
function generateToken($userId) {
    $pdo = Database::getInstance();
    // Generate a random token
    $token = bin2hex(random_bytes(32));
    // Set expiration: 1 day from now
    $expiresAt = date("Y-m-d H:i:s", strtotime("+1 day"));
    $stmt = $pdo->prepare("INSERT INTO api_tokens (user_id, token, expires_at) VALUES (:user_id, :token, :expires_at)");
    $stmt->execute([
        'user_id'    => $userId,
        'token'      => $token,
        'expires_at' => $expiresAt
    ]);
    return $token;
}

/**
 * Require a valid token. If not valid, terminate the request.
 */
function requireToken() {
    $token = getAuthToken();
    if (!$token) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Authentication token required']);
        exit;
    }
    $user = validateToken($token);
    if (!$user) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token']);
        exit;
    }
    return $user;
}
function tokenIsNearExpiry($token) {
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("SELECT expires_at FROM api_tokens WHERE token = :token");
    $stmt->execute(['token' => $token]);
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($record) {
        $expiresAt = strtotime($record['expires_at']);
        // Check if token expires within the next hour (3600 seconds)
        return ($expiresAt - time()) < 3600;
    }
    return false;
}