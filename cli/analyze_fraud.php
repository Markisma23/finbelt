<?php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

$fraudDetector = new FraudDetector();
$fraudDetector->analyzeAllUsers();

echo "Fraud analysis completed successfully.\n";
