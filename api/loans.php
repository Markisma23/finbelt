<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/classes/Loan.php';
require_once __DIR__ . '/api_util.php';

// Require a valid token; the user object is available if needed.
$user = requireToken();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $loans = Loan::getAllLoans();
        echo json_encode(['status' => 'success', 'data' => $loans]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
