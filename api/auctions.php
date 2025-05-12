<?php
// api/auctions.php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

header('Content-Type: application/json');

// In this demo, we assume that auctions are public information.
// In a real app, you might restrict access or include more security.
$auctionObj = new Auction();
$auctions = $auctionObj->getActiveAuctions();

echo json_encode([
    'status' => 'success',
    'data' => $auctions
]);
?>
