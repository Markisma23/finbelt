<?php
// api/index.php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

header('Content-Type: application/json');

// Check for API action, default to error.
$action = isset($_GET['action']) ? trim($_GET['action']) : '';

switch ($action) {
    case 'loans':
        require 'loans.php';
        break;
    case 'auctions':
        require 'auctions.php';
        break;
    case 'user':
        require 'user.php';
        break;
    default:
        echo json_encode(['error' => 'Invalid API action specified.']);
        break;
}
?>
