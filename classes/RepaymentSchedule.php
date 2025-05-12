<?php
class RepaymentSchedule {
    private $db;
    // Default term in months (can be overridden later, or derived from the loan product)
    private $termMonths = 12;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Generate a repayment schedule for a loan.
     *
     * @param int $loanId The loan identifier.
     * @param float $principal The original loan amount.
     * @param float $monthlyInterestRate The monthly interest rate (e.g., 0.35 for 35% per month).
     * @param int $termMonths Optional number of months over which the loan is to be repaid.
     * @param string $startDate The starting date for the first installment (YYYY-MM-DD).
     * @return array An array containing the schedule records.
     */
    public function generateSchedule($loanId, $principal, $monthlyInterestRate, $termMonths = null, $startDate = null) {
        if ($termMonths === null) {
            $termMonths = $this->termMonths;
        }
        if ($startDate === null) {
            $startDate = date('Y-m-d');
        }

        // For simplicity, we'll assume equal installments covering both principal and interest.
        // Total interest over the life of the loan (simple interest)
        $totalInterest = $principal * $monthlyInterestRate * $termMonths;
        // Total amount to be repaid.
        $totalRepayment = $principal + $totalInterest;
        // Equal installment amount.
        $installmentAmount = round($totalRepayment / $termMonths, 2);
        // For principal & interest breakdown per installment.
        $principalPerInstallment = round($principal / $termMonths, 2);
        $interestPerInstallment = round($totalInterest / $termMonths, 2);

        $schedule = [];
        $currentDate = new DateTime($startDate);

        // Remove any existing schedule for the loan.
        $stmtDelete = $this->db->prepare("DELETE FROM repayment_schedules WHERE loan_id = ?");
        $stmtDelete->execute([$loanId]);

        for ($i = 1; $i <= $termMonths; $i++) {
            $dueDate = $currentDate->format('Y-m-d');
            $record = [
                'loan_id' => $loanId,
                'installment_number' => $i,
                'due_date' => $dueDate,
                'principal_due' => $principalPerInstallment,
                'interest_due' => $interestPerInstallment,
                'total_due' => $installmentAmount
            ];
            $schedule[] = $record;
            // Save record to the database.
            $stmt = $this->db->prepare("INSERT INTO repayment_schedules (loan_id, installment_number, due_date, principal_due, interest_due, total_due) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $loanId, $i, $dueDate, $principalPerInstallment, $interestPerInstallment, $installmentAmount
            ]);
            // Move to the next month.
            $currentDate->modify('+1 month');
        }
        return $schedule;
    }

    /**
     * Retrieve the repayment schedule for a given loan.
     *
     * @param int $loanId
     * @return array
     */
    public function getSchedule($loanId) {
        $stmt = $this->db->prepare("SELECT * FROM repayment_schedules WHERE loan_id = ? ORDER BY installment_number ASC");
        $stmt->execute([$loanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
