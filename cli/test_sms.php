<?php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

$smsNotifier = new SMSNotifier();
$toPhone = '+260712345678';  // Update with a test number.
$message = "Test SMS: This is a test notification from Finbelt Microfinance.";
$result = $smsNotifier->sendSMS($toPhone, $message);

if ($result['success']) {
    echo "SMS sent successfully. Transaction ID: " . $result['transaction_id'] . "\n";
} else {
    echo "Failed to send SMS: " . $result['message'] . "\n";
}
