<?php
class DataExport {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Export data as CSV.
     *
     * @param array $data The multidimensional array of data to export.
     * @param array $headers The headers for the CSV columns.
     * @param string $filename The download filename.
     */
    public function exportCSV(array $data, array $headers, $filename = 'export.csv') {
        // Disable caching.
        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename={$filename}");
        header("Pragma: no-cache");
        header("Expires: 0");
        
        $output = fopen('php://output', 'w');
        
        // Output headers.
        fputcsv($output, $headers);
        
        // Output each row.
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
        exit();
    }

    /**
     * Retrieve loans data.
     *
     * @return array
     */
    public function getLoansData() {
        $stmt = $this->db->query("SELECT id, user_id, amount, collateral, status, applied_at FROM loans");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve repayments data.
     *
     * @return array
     */
    public function getRepaymentsData() {
        $stmt = $this->db->query("SELECT id, loan_id, amount, paid_at FROM repayments");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve auctions data.
     *
     * @return array
     */
    public function getAuctionsData() {
        $stmt = $this->db->query("SELECT id, loan_id, collateral, start_price, current_price, status, created_at, auction_end FROM auctions");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve users data.
     *
     * @return array
     */
    public function getUsersData() {
        $stmt = $this->db->query("SELECT id, username, email, full_name, phone, role, created_at FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
