<?php
class SMSNotifier {
    /**
     * Send an SMS message.
     *
     * @param string $toPhone The recipient’s phone number.
     * @param string $message The content of the SMS message.
     * @return array Returns an associative array with success status, message, and optionally a transaction ID.
     */
    public function sendSMS($toPhone, $message) {
        // Simulated sending: In production, integrate with your SMS provider API using cURL or an SDK.
        // For example, using Twilio you’d make an API call to Twilio’s endpoint.
        
        // Sanitize and validate the phone number and message here.
        $toPhone = trim($toPhone);
        $message = trim($message);
        if (empty($toPhone) || empty($message)) {
            return [
                'success' => false,
                'message' => 'Invalid phone number or message content.'
            ];
        }
        
        // Simulate network latency and logging.
        error_log("Simulated sending SMS to {$toPhone}: {$message}");
        
        // Simulate a transaction ID.
        $transactionId = 'SMS' . strtoupper(uniqid());
        
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'message' => "SMS sent successfully to {$toPhone}."
        ];
    }
}
?>
