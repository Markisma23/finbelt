<?php
require_once 'Database.php';

class LoanRepayment {

    /**
     * Record a repayment for a loan.
     *
     * @param int $loanId The loan ID.
     * @param int $clientId The client ID.
     * @param float $paymentAmount The amount paid.
     * @param float $remainingBalance The outstanding balance after the payment.
     * @return bool True if recorded successfully.
     */
    public static function recordRepayment($loanId, $clientId, $paymentAmount, $remainingBalance) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO loan_repayments (loan_id, client_id, payment_amount, remaining_balance)
                               VALUES (:loan_id, :client_id, :payment_amount, :remaining_balance)");
        return $stmt->execute([
            'loan_id'           => $loanId,
            'client_id'         => $clientId,
            'payment_amount'    => $paymentAmount,
            'remaining_balance' => $remainingBalance
        ]);
    }

    /**
     * Retrieve repayment history for a given loan.
     *
     * @param int $loanId
     * @return array
     */
    public static function getRepaymentsByLoan($loanId) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM loan_repayments WHERE loan_id = :loan_id ORDER BY payment_date DESC");
        $stmt->execute(['loan_id' => $loanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve repayment history for a client.
     *
     * @param int $clientId
     * @return array
     */
    public static function getRepaymentHistory($clientId) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT lr.*, l.amount AS loan_amount 
                               FROM loan_repayments lr 
                               JOIN loans l ON lr.loan_id = l.id 
                               WHERE lr.client_id = :client_id 
                               ORDER BY lr.payment_date DESC");
        $stmt->execute(['client_id' => $clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
