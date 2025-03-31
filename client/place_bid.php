<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}
$user = unserialize($_SESSION['user']);
if ($user->role !== 'client') {
    header("Location: ../public/login.php");
    exit;
}
require_once __DIR__ . '/../includes/classes/Client.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auctionId = intval($_POST['auction_id']);
    $bidAmount = floatval($_POST['bid_amount']);
    try {
        $user->placeBid($auctionId, $bidAmount);
        header("Location: auctions.php");
        exit;
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>
