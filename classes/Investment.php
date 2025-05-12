<?php
class Investment {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Place an investment in a loan.
     *
     * @param int $investorId The investor's user ID.
     * @param int $loanId The loan ID.
     * @param float $amount The amount to invest.
     * @return bool|string Returns true on success or an error message.
     */
    public function invest($investorId, $loanId, $amount) {
        if ($amount <= 0) {
            return "Investment amount must be positive.";
        }
        
        // Optional: Check that the loan exists and is open for investment,
        // and that the amount does not exceed the remaining required funding.
        $stmt = $this->db->prepare("SELECT amount FROM loans WHERE id = ? AND status = 'approved'");
        $stmt->execute([$loanId]);
        $loan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$loan) {
            return "Loan not available for investment.";
        }
        
        // Optionally, check total investments so far to enforce a funding cap.
        $stmtInvested = $this->db->prepare("SELECT IFNULL(SUM(amount), 0) as total_invested FROM investments WHERE loan_id = ?");
        $stmtInvested->execute([$loanId]);
        $investmentData = $stmtInvested->fetch(PDO::FETCH_ASSOC);
        $totalInvested = (float)$investmentData['total_invested'];
        $remaining = (float)$loan['amount'] - $totalInvested;
        if ($amount > $remaining) {
            return "Investment exceeds remaining funding required (Remaining: ZMW " . number_format($remaining,2) . ").";
        }
        
        // Insert the investment record.
        $stmtInsert = $this->db->prepare("INSERT INTO investments (investor_id, loan_id, amount) VALUES (?, ?, ?)");
        $result = $stmtInsert->execute([$investorId, $loanId, $amount]);
        return $result ? true : "Failed to record your investment.";
    }

    /**
     * Retrieve all investments for a given investor.
     *
     * @param int $investorId
     * @return array
     */
    public function getInvestmentsByInvestor($investorId) {
        $stmt = $this->db->prepare("
            SELECT i.*, l.amount as loan_amount, l.applied_at, l.collateral
            FROM investments i
            JOIN loans l ON i.loan_id = l.id
            WHERE i.investor_id = ?
            ORDER BY i.invested_at DESC
        ");
        $stmt->execute([$investorId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve investments for a given loan (for admin review).
     *
     * @param int $loanId
     * @return array
     */
    public function getInvestmentsByLoan($loanId) {
        $stmt = $this->db->prepare("
            SELECT i.*, u.username
            FROM investments i
            JOIN users u ON i.investor_id = u.id
            WHERE i.loan_id = ?
            ORDER BY i.invested_at DESC
        ");
        $stmt->execute([$loanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
