<?php
class PaymentGateway {
    /**
     * Process a payment via an external payment gateway.
     *
     * @param float $amount Payment amount
     * @param int $userId The user making the payment
     * @param int $loanId The associated loan id
     * @return array Simulated response: [ 'success' => bool, 'transaction_id' => string, 'message' => string ]
     */
    public function processPayment($amount, $userId, $loanId) {
        // Simulated API call. In production, call your provider's API here.
        // For demonstration, we assume the payment always succeeds.
        $transactionId = 'TXN' . strtoupper(uniqid());
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'message' => "Payment of ZMW " . number_format($amount, 2) . " was processed successfully."
        ];
    }
}
?>
