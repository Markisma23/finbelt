<?php
class PaymentReminder {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get loans that are due for repayment or are overdue.
     *
     * For this demo we assume that each loan has a repayment due date added 
     * by the admin or calculated from the loan's applied_at date.
     *
     * @return array
     */
    public function getDueLoans() {
        // For example, we consider loans due for reminder if they were applied over 30 days ago.
        // In production, you could have a separate due_date field in the loans table.
        $stmt = $this->db->prepare("SELECT * FROM loans WHERE DATEDIFF(NOW(), applied_at) >= 30 AND status = 'approved'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Send a payment reminder to a user.
     *
     * @param int $userId
     * @param string $loanId
     * @param float $outstanding Amount outstanding
     * @return bool
     */
    public function sendReminder($userId, $loanId, $outstanding) {
        // Here, you would integrate with an email or SMS service.
        // For our demonstration, we simulate by writing a log entry.
        $userObj = new User();
        $user = $userObj->getUserById($userId);
        $to = $user ? $user['email'] : 'unknown@example.com';
        $subject = "Payment Reminder for Loan #$loanId";
        $body = "Dear {$user['username']},\n\nThis is a reminder that you have an outstanding balance of ZMW " . number_format($outstanding, 2) . " for Loan #$loanId. Please make a repayment at your earliest convenience.\n\nThank you.";
        // For demo purposes, we simply log the email.
        error_log("Reminder sent to $to: $subject\n$body\n");
        return true;
    }

    /**
     * Check due loans and send reminders.
     */
    public function processReminders() {
        $loans = $this->getDueLoans();
        $interestCalc = new InterestCalculator();
        foreach ($loans as $loan) {
            $loanId = $loan['id'];
            $userId = $loan['user_id'];
            // Calculate outstanding principal using the interest module.
            $outstanding = (new InterestCalculator())->getOutstandingPrincipal($loanId);
            // Send reminder only if outstanding exists.
            if ($outstanding > 0) {
                $this->sendReminder($userId, $loanId, $outstanding);
            }
        }
    }
}
?>
