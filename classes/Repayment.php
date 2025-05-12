<?php
class Repayment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Record a repayment for a given loan.
     *
     * @param int $loanId
     * @param float $amount
     * @return bool
     */
    public function makeRepayment($loanId, $amount) {
        if ($amount <= 0) {
            return false;
        }
        $stmt = $this->db->prepare("INSERT INTO repayments (loan_id, amount) VALUES (?, ?)");
        return $stmt->execute([$loanId, $amount]);
    }

    /**
     * Get all repayment records for a specific loan.
     *
     * @param int $loanId
     * @return array
     */
    public function getLoanRepayments($loanId) {
        $stmt = $this->db->prepare("SELECT * FROM repayments WHERE loan_id = ? ORDER BY paid_at DESC");
        $stmt->execute([$loanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Calculate the total amount repaid for a given loan.
     *
     * @param int $loanId
     * @return float
     */
    public function calculateTotalRepayments($loanId) {
        $stmt = $this->db->prepare("SELECT SUM(amount) as total FROM repayments WHERE loan_id = ?");
        $stmt->execute([$loanId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ? $result['total'] : 0;
    }
}
?>
