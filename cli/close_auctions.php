<?php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

$auction = new Auction();
$auction->closeExpiredAuctions();

echo "Expired auctions processed and closed successfully.\n";
