<?php
class Report {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Total number of loans.
     *
     * @return int
     */
    public function getTotalLoans() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM loans");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    /**
     * Total sum of loans applied.
     *
     * @return float
     */
    public function getSumOfLoans() {
        $stmt = $this->db->query("SELECT SUM(amount) as total FROM loans");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ? (float)$result['total'] : 0;
    }

    /**
     * Total number of repayments made.
     *
     * @return int
     */
    public function getTotalRepaymentsCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM repayments");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    /**
     * Total sum repaid.
     *
     * @return float
     */
    public function getTotalRepayments() {
        $stmt = $this->db->query("SELECT SUM(amount) as total FROM repayments");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] ? (float)$result['total'] : 0;
    }

    /**
     * Count of active auctions.
     *
     * @return int
     */
    public function getActiveAuctionsCount() {
        $stmt = $this->db->query("SELECT COUNT(*) as total FROM auctions WHERE status = 'active'");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['total'];
    }

    /**
     * Get KYC document summaries.
     *
     * @return array Returns counts by document status.
     */
    public function getKycSummary() {
        $stmt = $this->db->query("
            SELECT status, COUNT(*) as total 
            FROM kyc_documents 
            GROUP BY status
        ");
        $summary = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $summary[$row['status']] = $row['total'];
        }
        return $summary;
    }

    /**
     * Get outstanding balance for loans.
     * For demonstration purposes, this calculates the sum of each loan's original amount 
     * less its total repayments.
     *
     * @return float
     */
    public function getOutstandingLoanBalance() {
        $stmt = $this->db->query("
            SELECT l.id, l.amount,
            (SELECT IFNULL(SUM(r.amount),0) FROM repayments r WHERE r.loan_id = l.id) as repaid
            FROM loans l
        ");
        $balance = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $balance += ($row['amount'] - $row['repaid']);
        }
        return $balance;
    }
}
?>
