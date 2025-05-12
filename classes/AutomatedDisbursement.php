<?php
class AutomatedDisbursement {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Schedule a loan disbursement.
     * This method creates a record in the loan_disbursements table.
     *
     * @param int $loanId
     * @param float $amount
     * @param string $scheduledAt DateTime string when disbursement is scheduled.
     * @return bool
     */
    public function scheduleDisbursement($loanId, $amount, $scheduledAt) {
        $stmt = $this->db->prepare("INSERT INTO loan_disbursements (loan_id, disbursement_amount, scheduled_at) VALUES (?, ?, ?)");
        return $stmt->execute([$loanId, $amount, $scheduledAt]);
    }
    
    /**
     * Process scheduled disbursements that are due.
     * In production, integrate with an external payment API.
     */
    public function processDueDisbursements() {
        // Retrieve disbursements scheduled for now or earlier and still pending.
        $stmt = $this->db->prepare("SELECT * FROM loan_disbursements WHERE status = 'scheduled' AND scheduled_at <= NOW()");
        $stmt->execute();
        $dueDisbursements = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($dueDisbursements as $disbursement) {
            // Simulate external API call for transferring funds.
            $success = $this->simulateDisbursement($disbursement['loan_id'], $disbursement['disbursement_amount']);
            if ($success) {
                $updateStmt = $this->db->prepare("UPDATE loan_disbursements SET status = 'disbursed', disbursed_at = NOW() WHERE id = ?");
                $updateStmt->execute([$disbursement['id']]);
            } else {
                $updateStmt = $this->db->prepare("UPDATE loan_disbursements SET status = 'failed', remarks = ? WHERE id = ?");
                $updateStmt->execute(["Disbursement failed due to API error", $disbursement['id']]);
            }
        }
    }
    
    /**
     * Simulate an external disbursement API.
     *
     * @param int $loanId
     * @param float $amount
     * @return bool Always returns true in this simulation.
     */
    private function simulateDisbursement($loanId, $amount) {
        // In production, you would send an API request here.
        error_log("Simulating disbursement of ZMW " . number_format($amount, 2) . " for Loan ID: {$loanId}");
        return true;
    }
    
    /**
     * Retrieve the disbursement record for a given loan.
     *
     * @param int $loanId
     * @return array|false
     */
    public function getDisbursement($loanId) {
        $stmt = $this->db->prepare("SELECT * FROM loan_disbursements WHERE loan_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$loanId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
