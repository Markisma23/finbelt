<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/classes/Auction.php';
require_once __DIR__ . '/api_util.php';

// Require token
$user = requireToken();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $auctions = Auction::getActiveAuctions();
        echo json_encode(['status' => 'success', 'data' => $auctions]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
