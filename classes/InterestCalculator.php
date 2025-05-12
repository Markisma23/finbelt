<?php
class InterestCalculator {
    private $db;
    private $monthlyRate = 0.35; // 35% per month

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get loan details by loan id.
     *
     * @param int $loanId
     * @return array|false
     */
    private function getLoan($loanId) {
        $stmt = $this->db->prepare("SELECT * FROM loans WHERE id = ?");
        $stmt->execute([$loanId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Calculate the outstanding principal for a given loan.
     * Assumes that repayments are applied solely to principal.
     *
     * @param int $loanId
     * @return float
     */
    public function getOutstandingPrincipal($loanId) {
        $loan = $this->getLoan($loanId);
        if (!$loan) {
            return 0;
        }
        // Get total repayments
        $stmt = $this->db->prepare("SELECT IFNULL(SUM(amount), 0) as total FROM repayments WHERE loan_id = ?");
        $stmt->execute([$loanId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $totalRepayments = $result['total'] ? (float)$result['total'] : 0.0;
        $outstanding = (float)$loan['amount'] - $totalRepayments;
        return ($outstanding > 0) ? $outstanding : 0;
    }

    /**
     * Calculate the interest accrued for a given loan.
     * Uses the difference in months (or partial months) from the loan's applied_at date to now.
     *
     * @param int $loanId
     * @return float
     */
    public function getInterestAccrued($loanId) {
        $loan = $this->getLoan($loanId);
        if (!$loan) {
            return 0;
        }
        $appliedDate = new DateTime($loan['applied_at']);
        $currentDate = new DateTime();
        $interval = $appliedDate->diff($currentDate);
        // Convert the time difference to months (approximate: days/30)
        $monthsElapsed = $interval->days / 30;
        
        // Calculate outstanding principal.
        $outstandingPrincipal = $this->getOutstandingPrincipal($loanId);
        // Simple interest calculation:
        $interest = $outstandingPrincipal * $this->monthlyRate * $monthsElapsed;
        
        return $interest;
    }
}
?>
