<?php
require_once 'Database.php';
require_once 'LoanRepayment.php';
require_once 'Loan.php';
require_once 'EmailNotification.php';

class PaymentSchedule {

    /**
     * Calculate and update the next due date for a loan.
     * This example assumes monthly repayments.
     *
     * @param int $loanId
     * @return bool True if updated successfully.
     */
    public static function updateNextDueDate($loanId) {
        $pdo = Database::getInstance();
        
        // Retrieve the current next due date (if set) or use today's date.
        $stmt = $pdo->prepare("SELECT next_due_date, created_at FROM loans WHERE id = :loanId");
        $stmt->execute(['loanId' => $loanId]);
        $loan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$loan) {
            throw new Exception("Loan not found");
        }
        
        // If no next due date, initialize based on the loan approval date (or creation date)
        $currentDue = $loan['next_due_date'] ? $loan['next_due_date'] : $loan['created_at'];
        // Add one month to the current due date
        $nextDue = date('Y-m-d', strtotime($currentDue . ' +1 month'));
        
        $stmtUpdate = $pdo->prepare("UPDATE loans SET next_due_date = :nextDue WHERE id = :loanId");
        return $stmtUpdate->execute(['nextDue' => $nextDue, 'loanId' => $loanId]);
    }
    
    /**
     * Check if a loan is fully repaid based on repayment history.
     *
     * @param int $loanId
     * @return bool True if fully repaid.
     */
    public static function isLoanFullyRepaid($loanId) {
        $pdo = Database::getInstance();
        
        // Retrieve the original loan amount
        $stmt = $pdo->prepare("SELECT amount FROM loans WHERE id = :loanId");
        $stmt->execute(['loanId' => $loanId]);
        $loan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$loan) {
            throw new Exception("Loan not found");
        }
        
        $originalAmount = $loan['amount'];
        
        // Sum all repayments for this loan
        $stmtSum = $pdo->prepare("SELECT SUM(payment_amount) as total_paid FROM loan_repayments WHERE loan_id = :loanId");
        $stmtSum->execute(['loanId' => $loanId]);
        $result = $stmtSum->fetch(PDO::FETCH_ASSOC);
        $totalPaid = $result['total_paid'] ? floatval($result['total_paid']) : 0;
        
        return $totalPaid >= $originalAmount;
    }
    
    /**
     * Send a payment reminder email if the next due date is overdue.
     *
     * @param int $loanId
     * @return bool
     */
    public static function sendPaymentReminder($loanId) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT l.next_due_date, u.email, u.name, l.id as loan_id FROM loans l JOIN users u ON l.client_id = u.id WHERE l.id = :loanId");
        $stmt->execute(['loanId' => $loanId]);
        $loanInfo = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$loanInfo) {
            throw new Exception("Loan not found for reminder");
        }
        
        $today = date('Y-m-d');
        if ($loanInfo['next_due_date'] < $today) {
            // Send reminder email
            $emailNotifier = new EmailNotification();
            $subject = "Loan Repayment Reminder for Loan #{$loanInfo['loan_id']}";
            $body = "Dear " . htmlspecialchars($loanInfo['name']) . ",<br><br>Your loan repayment for Loan #{$loanInfo['loan_id']} is overdue. Please make your payment as soon as possible to avoid penalties.<br><br>Best regards,<br>Finbelt Microfinance";
            return $emailNotifier->sendEmail($loanInfo['email'], $subject, $body);
        }
        return true;
    }
    
    /**
     * Process a loan: update next due date if payment was made,
     * check if fully repaid, and send reminders if overdue.
     *
     * @param int $loanId
     */
    public static function processLoan($loanId) {
        // If the loan is fully repaid, update the status.
        if (self::isLoanFullyRepaid($loanId)) {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("UPDATE loans SET status = 'repaid' WHERE id = :loanId");
            $stmt->execute(['loanId' => $loanId]);
        } else {
            // Otherwise, update the next due date (if a repayment was made)
            self::updateNextDueDate($loanId);
            // Send a reminder if overdue
            self::sendPaymentReminder($loanId);
        }
    }
}
