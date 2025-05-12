<?php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

$defaultManager = new DefaultManager();
$defaultManager->processDefaults();

echo "Default processing completed successfully.\n";
