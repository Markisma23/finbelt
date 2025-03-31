<?php
// This script is intended to be run from the command line (cron job).
require_once __DIR__ . '/../includes/classes/Database.php';

$pdo = Database::getInstance();
$stmt = $pdo->prepare("DELETE FROM api_tokens WHERE expires_at < NOW()");
if ($stmt->execute()) {
    echo "Expired tokens cleaned up successfully.\n";
} else {
    echo "Error cleaning up expired tokens.\n";
}
