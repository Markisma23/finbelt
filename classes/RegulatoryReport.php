<?php
class RegulatoryReport {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Generate a report for a given date range.
     *
     * @param string $startDate Start date in 'YYYY-MM-DD' format.
     * @param string $endDate End date in 'YYYY-MM-DD' format.
     * @return array
     */
    public function generateReport($startDate, $endDate) {
        $report = [];
        
        // Total number of loans and total loan amount applied during the period.
        $stmt = $this->db->prepare("SELECT COUNT(*) as total_loans, SUM(amount) as total_amount FROM loans WHERE applied_at BETWEEN ? AND ?");
        $stmt->execute([$startDate, $endDate]);
        $report['loans'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Total repayments made during the period.
        $stmt = $this->db->prepare("SELECT COUNT(*) as total_repayments, SUM(amount) as total_repaid FROM repayments WHERE paid_at BETWEEN ? AND ?");
        $stmt->execute([$startDate, $endDate]);
        $report['repayments'] = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Number of defaulted loans initiated during the period.
        $stmt = $this->db->prepare("SELECT COUNT(*) as total_defaults FROM loans WHERE status = 'defaulted' AND applied_at BETWEEN ? AND ?");
        $stmt->execute([$startDate, $endDate]);
        $report['defaults'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['total_defaults'];
        
        // Average loan amount applied.
        $stmt = $this->db->prepare("SELECT AVG(amount) as avg_loan FROM loans WHERE applied_at BETWEEN ? AND ?");
        $stmt->execute([$startDate, $endDate]);
        $report['avg_loan'] = $stmt->fetch(PDO::FETCH_ASSOC)['avg_loan'];
        
        // Number of active auctions created during the period.
        $stmt = $this->db->prepare("SELECT COUNT(*) as active_auctions FROM auctions WHERE created_at BETWEEN ? AND ? AND status = 'active'");
        $stmt->execute([$startDate, $endDate]);
        $report['active_auctions'] = (int)$stmt->fetch(PDO::FETCH_ASSOC)['active_auctions'];
        
        return $report;
    }
}
?>
