<?php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

$reminder = new PaymentReminder();
$reminder->processReminders();

echo "Payment reminders processed successfully.\n";
