<?php
class AutomatedRepaymentCollection {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Schedule a repayment collection record.
     *
     * @param int $loanId
     * @param float $amount
     * @param string $scheduledAt
     * @return bool
     */
    public function scheduleCollection($loanId, $amount, $scheduledAt) {
        $stmt = $this->db->prepare("INSERT INTO repayment_collections (loan_id, collection_amount, scheduled_at) VALUES (?, ?, ?)");
        return $stmt->execute([$loanId, $amount, $scheduledAt]);
    }

    /**
     * Process scheduled repayment collections.
     */
    public function processDueCollections() {
        $stmt = $this->db->prepare("SELECT * FROM repayment_collections WHERE status = 'scheduled' AND scheduled_at <= NOW()");
        $stmt->execute();
        $dueCollections = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($dueCollections as $collection) {
            $success = $this->simulateCollection($collection['loan_id'], $collection['collection_amount']);
            if ($success) {
                $updateStmt = $this->db->prepare("UPDATE repayment_collections SET status = 'collected', collected_at = NOW() WHERE id = ?");
                $updateStmt->execute([$collection['id']]);
            } else {
                $updateStmt = $this->db->prepare("UPDATE repayment_collections SET status = 'failed', remarks = ? WHERE id = ?");
                $updateStmt->execute(["Collection failed due to API error", $collection['id']]);
            }
        }
    }
    
    /**
     * Simulate a collection API call.
     *
     * @param int $loanId
     * @param float $amount
     * @return bool
     */
    private function simulateCollection($loanId, $amount) {
        // In production, integrate with an automatic debit system.
        error_log("Simulating repayment collection of ZMW " . number_format($amount, 2) . " for Loan ID: {$loanId}");
        return true;
    }
    
    /**
     * Retrieve collections for a given loan.
     *
     * @param int $loanId
     * @return array
     */
    public function getCollections($loanId) {
        $stmt = $this->db->prepare("SELECT * FROM repayment_collections WHERE loan_id = ? ORDER BY created_at DESC");
        $stmt->execute([$loanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
