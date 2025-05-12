<?php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

$jobQueue = new JobQueue();
$jobQueue->processJobs();

echo "Job processing completed successfully.\n";
