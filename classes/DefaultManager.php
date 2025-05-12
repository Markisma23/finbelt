<?php
class DefaultManager {
    private $db;
    // Set the default threshold in days (for example, 60 days overdue)
    private $defaultThresholdDays = 60;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Check for loans that have not been repaid sufficiently after the due period,
     * then mark them as defaulted and trigger collateral auction.
     */
    public function processDefaults() {
        // In this simplified example, we assume that a loan is considered overdue/defaulted if:
        // The loan is approved, and it has been more than $defaultThresholdDays since applied_at.
        // In a production system, you might include additional checks (e.g., missed payments, loan due date).
        $stmt = $this->db->prepare("SELECT * FROM loans WHERE status = 'approved' AND DATEDIFF(NOW(), applied_at) >= ?");
        $stmt->execute([$this->defaultThresholdDays]);
        $loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach($loans as $loan) {
            $loanId = $loan['id'];
            // Calculate outstanding principal using the InterestCalculator logic.
            $interestCalc = new InterestCalculator();
            $outstanding = $interestCalc->getOutstandingPrincipal($loanId);
            
            // For this demonstration, if there is any outstanding, mark as default.
            if ($outstanding > 0) {
                $this->markLoanAsDefaulted($loanId);
                
                // Create a collateral auction if it does not already exist.
                $auction = new Auction();
                // Check if an auction for this loan already exists.
                $stmtCheck = $this->db->prepare("SELECT id FROM auctions WHERE loan_id = ? LIMIT 1");
                $stmtCheck->execute([$loanId]);
                if (!$stmtCheck->fetch(PDO::FETCH_ASSOC)) {
                    // For simplicity, set the start price to the outstanding balance.
                    // Set auction_end to 7 days from now.
                    $auctionEnd = date('Y-m-d H:i:s', strtotime('+7 days'));
                    $auction->createAuction($loanId, $loan['collateral'], $outstanding, $auctionEnd);
                }
                
                // Notify the borrower and admin about the default.
                $notification = new Notification();
                $notification->addNotification($loan['user_id'], null, "Your loan #{$loanId} is now defaulted due to overdue payments. Collateral auction has been initiated.");

                // Log the default event.
    $audit = new AuditLog();
    $audit->logEvent($loan['user_id'], 'loan_default', "Loan #{$loanId} defaulted due to overdue payments. Collateral auction initiated.");
            }
        }
    }
    
    /**
     * Mark a given loan as defaulted.
     *
     * @param int $loanId
     * @return bool
     */
    private function markLoanAsDefaulted($loanId) {
        $stmt = $this->db->prepare("UPDATE loans SET status = 'defaulted' WHERE id = ?");
        return $stmt->execute([$loanId]);
    }
}
?>
