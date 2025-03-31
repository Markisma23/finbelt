<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../includes/classes/EmailNotification.php';

class EmailNotificationTest extends TestCase {
    public function testSendEmail() {
        $emailNotifier = new EmailNotification();
        // For testing purposes, set a recipient that you control or use a testing mail server.
        $result = $emailNotifier->sendEmail('testrecipient@example.com', 'Test Email', '<p>This is a test email.</p>');
        // Assert that sending the email returned true.
        $this->assertTrue($result, "Email should be sent successfully.");
    }
}
