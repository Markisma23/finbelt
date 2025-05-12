<?php
require_once('tcpdf/tcpdf.php'); // Adjust the path as needed.

class LoanContractGenerator {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Generate a PDF contract for a specific loan.
     *
     * @param int $loanId
     * @return string|false The file path of the generated contract, or false on failure.
     */
    public function generateContract($loanId) {
        // Retrieve loan details.
        $stmt = $this->db->prepare("SELECT l.*, u.full_name, u.email FROM loans l JOIN users u ON l.user_id = u.id WHERE l.id = ?");
        $stmt->execute([$loanId]);
        $loan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$loan) {
            return false;
        }
        
        // Create a new PDF document using TCPDF.
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($loan['full_name']);
        $pdf->SetTitle('Loan Contract #' . $loanId);
        $pdf->AddPage();
        
        // Create a simple template for the contract.
        $html = '<h1>Loan Contract</h1>';
        $html .= '<p>This Loan Contract ("Contract") is made between Finbelt Microfinance ("Lender") and ' . htmlspecialchars($loan['full_name']) . ' ("Borrower").</p>';
        $html .= '<h2>Loan Details</h2>';
        $html .= '<p><strong>Loan ID:</strong> ' . $loan['id'] . '</p>';
        $html .= '<p><strong>Amount:</strong> ZMW ' . number_format($loan['amount'],2) . '</p>';
        $html .= '<p><strong>Collateral:</strong> ' . htmlspecialchars($loan['collateral']) . '</p>';
        $html .= '<h2>Terms and Conditions</h2>';
        $html .= '<p>By accepting this contract, the Borrower agrees to the repayment terms and conditions outlined by the Lender.</p>';
        $html .= '<p>Date: ' . date('Y-m-d') . '</p>';
        
        // Write the HTML content to the PDF.
        $pdf->writeHTML($html, true, false, true, false, '');
        
        // Define a file path to save the PDF.
        $uploadDir = __DIR__ . '/../uploads/contracts/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        $filePath = $uploadDir . 'loan_contract_' . $loanId . '_' . time() . '.pdf';
        
        // Output the PDF to file.
        $pdf->Output($filePath, 'F');
        
        // Save record into loan_contracts table.
        $stmtInsert = $this->db->prepare("INSERT INTO loan_contracts (loan_id, contract_path) VALUES (?, ?)");
        if ($stmtInsert->execute([$loanId, $filePath])) {
            return $filePath;
        }
        return false;
    }

    /**
     * Retrieve the contract for a given loan.
     *
     * @param int $loanId
     * @return array|false
     */
    public function getContract($loanId) {
        $stmt = $this->db->prepare("SELECT * FROM loan_contracts WHERE loan_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$loanId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Mark a contract as signed.
     *
     * @param int $contractId
     * @return bool
     */
    public function markAsSigned($contractId) {
        $stmt = $this->db->prepare("UPDATE loan_contracts SET status = 'signed', signed_at = NOW() WHERE id = ?");
        return $stmt->execute([$contractId]);
    }
}
?>
