<?php
class RiskAnalyzer {
    private $db;
    // Base risk score for every borrower.
    private $baseScore = 50;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Calculate the risk score for a given user.
     *
     * This example algorithm:
     * - Starts with a base score (e.g., 50).
     * - Adds penalty points for each defaulted loan (e.g., +30 per default).
     * - Adds penalty points for each approved loan overdue more than 30 days (e.g., +10 per overdue).
     * - Subtracts points for each fully repaid loan (e.g., -5 per repaid loan) as a reward.
     *
     * Lower scores represent lower risk.
     *
     * @param int $userId
     * @return array ['score' => float, 'risk_level' => string]
     */
    public function calculateRiskScore($userId) {
        $score = $this->baseScore;

        // Count defaulted loans.
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM loans WHERE user_id = ? AND status = 'defaulted'");
        $stmt->execute([$userId]);
        $defaults = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $score += $defaults * 30;
        
        // Count loans that are approved and older than 30 days (potential overdue).
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM loans WHERE user_id = ? AND status = 'approved' AND DATEDIFF(NOW(), applied_at) > 30");
        $stmt->execute([$userId]);
        $overdue = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $score += $overdue * 10;
        
        // Count fully repaid loans.
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM loans WHERE user_id = ? AND status = 'approved' AND amount = (SELECT IFNULL(SUM(amount),0) FROM repayments WHERE loan_id = loans.id)");
        $stmt->execute([$userId]);
        $fullyRepaid = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total'];
        $score -= $fullyRepaid * 5;
        
        // Ensure score does not drop below a minimum or above a maximum.
        if ($score < 0) {
            $score = 0;
        }
        if ($score > 100) {
            $score = 100;
        }

        // Assign risk level based on score thresholds.
        $riskLevel = 'low';
        if ($score > 70) {
            $riskLevel = 'high';
        } elseif ($score > 50) {
            $riskLevel = 'medium';
        }

        return ['score' => $score, 'risk_level' => $riskLevel];
    }
}
?>
