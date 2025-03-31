<?php
// This script is intended to be run from the command line (via a cron job)
require_once __DIR__ . '/../includes/classes/PaymentSchedule.php';
require_once __DIR__ . '/../includes/classes/Loan.php';
require_once __DIR__ . '/../includes/classes/Database.php';

// Get all approved loans that are not yet marked as repaid.
$pdo = Database::getInstance();
$stmt = $pdo->query("SELECT id FROM loans WHERE status = 'approved'");
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($loans as $loan) {
    try {
        PaymentSchedule::processLoan($loan['id']);
        echo "Processed Loan ID: " . $loan['id'] . "\n";
    } catch (Exception $e) {
        echo "Error processing Loan ID " . $loan['id'] . ": " . $e->getMessage() . "\n";
    }
}
