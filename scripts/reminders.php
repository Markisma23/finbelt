<?php
// CLI entry point, e.g. php scripts/reminders.php
require_once __DIR__ . '/../bootstrap.php';
use App\Notification;
Notification::scheduleReminders();
?>