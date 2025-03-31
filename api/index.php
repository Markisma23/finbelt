<?php
header('Content-Type: application/json');

echo json_encode([
    'endpoints' => [
        'loans'    => '/finbelt/api/loans.php',
        'auctions' => '/finbelt/api/auctions.php',
        'bids'     => '/finbelt/api/bids.php'
    ]
]);
