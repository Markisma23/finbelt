<?php
// api/loans.php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

// Set header to JSON output.
header('Content-Type: application/json');

// For this demo, we check for a logged-in user via session.
if (!isset($_SESSION['user'])) {
    echo json_encode(['error' => 'Unauthorized - please log in.']);
    exit();
}

$userId = $_SESSION['user']['id'];
$loanObj = new Loan();
$loans = $loanObj->getUserLoans($userId);

// Respond with loans data.
echo json_encode([
    'status' => 'success',
    'data' => $loans
]);
?>
