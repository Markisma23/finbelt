<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/classes/Database.php';
require_once __DIR__ . '/../includes/classes/Bidding.php';
require_once __DIR__ . '/api_util.php';

// Require token
$user = requireToken();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['auction_id'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'auction_id parameter is required']);
        exit;
    }
    $auctionId = intval($_GET['auction_id']);
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM bids WHERE auction_id = :auctionId");
    $stmt->execute(['auctionId' => $auctionId]);
    $bids = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $bids]);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents("php://input"), true);
    if (!isset($input['auction_id'], $input['bid_amount'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Missing required fields: auction_id, bid_amount']);
        exit;
    }
    // For security, you can use the client ID from the token rather than trusting the input.
    $clientId = $user->id;
    try {
        Bidding::recordBid($clientId, $input['auction_id'], $input['bid_amount']);
        echo json_encode(['status' => 'success', 'message' => 'Bid recorded successfully']);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
