<?php
class FraudDetector {
    private $db;
    
    // Define thresholds for triggering a fraud flag.
    private $defaultThreshold = 2;      // More than 2 defaults flag suspicious.
    private $defaultRatioThreshold = 0.5; // If defaults constitute more than 50% of all loans.
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Analyze a single user’s loan activity and flag potential fraud if thresholds are exceeded.
     *
     * @param int $userId
     * @return void
     */
    public function analyzeUser($userId) {
        // Total number of loans by the user.
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM loans WHERE user_id = ?");
        $stmt->execute([$userId]);
        $totalLoans = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        if ($totalLoans == 0) {
            return; // No loans, nothing to analyze.
        }
        
        // Count defaults.
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM loans WHERE user_id = ? AND status = 'defaulted'");
        $stmt->execute([$userId]);
        $defaultCount = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Compute ratio of defaults to total.
        $defaultRatio = $defaultCount / $totalLoans;
        
        $flagged = false;
        $flagReason = '';
        $riskScore = 0;
        
        // Rule 1: Number of defaults exceeds threshold.
        if ($defaultCount > $this->defaultThreshold) {
            $flagged = true;
            $flagReason = "More than {$this->defaultThreshold} defaulted loans ({$defaultCount} defaults out of {$totalLoans}).";
            $riskScore += 50;
        }
        
        // Rule 2: Default ratio exceeds threshold.
        if ($defaultRatio > $this->defaultRatioThreshold) {
            $flagged = true;
            // Accumulate risk score based on percentage beyond threshold.
            $extraRatio = $defaultRatio - $this->defaultRatioThreshold;
            $riskScore += $extraRatio * 100;
            if ($flagReason) {
                $flagReason .= " Also, defaults account for " . round($defaultRatio * 100) . "% of all loans.";
            } else {
                $flagReason = "Defaults account for " . round($defaultRatio * 100) . "% of all loans.";
            }
        }
        
        // If flagged, insert a record into the fraud_flags table.
        if ($flagged) {
            $stmt = $this->db->prepare("INSERT INTO fraud_flags (user_id, flag_reason, risk_score) VALUES (?, ?, ?)");
            $stmt->execute([$userId, $flagReason, $riskScore]);
        }
    }
    
    /**
     * Analyze all users.  
     * In production, you might run this as a background process and handle flagged records accordingly.
     */
    public function analyzeAllUsers() {
        $stmt = $this->db->query("SELECT id FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($users as $user) {
            $this->analyzeUser($user['id']);
        }
    }
    
    /**
     * Retrieve all fraud flag records.
     *
     * @return array
     */
    public function getFraudFlags() {
        $stmt = $this->db->query("SELECT f.*, u.username FROM fraud_flags f JOIN users u ON f.user_id = u.id ORDER BY f.created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
