<?php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

$collection = new AutomatedRepaymentCollection();
$collection->processDueCollections();

echo "Repayment collection processing completed.\n";
