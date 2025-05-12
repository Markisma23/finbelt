<?php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

$disbursement = new AutomatedDisbursement();
$disbursement->processDueDisbursements();

echo "Disbursement processing completed.\n";
