<?php
namespace App;

use FPDF;
use App\Database;

class Contract {
    // Generates a PDF contract, saves it, and records it in the contracts table.
    public static function generate($clientId, $loanId) {
        // Fetch client & loan details
        \$db = Database::getInstance()->getConnection();
        \$stmt = \$db->prepare("
            SELECT c.name, c.nrc, c.country,
                   l.amount, l.interest_rate, l.due_date
            FROM loans l
            JOIN clients c ON l.client_id = c.user_id
            WHERE c.user_id = ? AND l.id = ?
        ");
        \$stmt->execute([\$clientId, \$loanId]);
        \$data = \$stmt->fetch(\PDO::FETCH_ASSOC);

        // Build PDF
        require_once __DIR__ . '/../lib/fpdf.php';
        \$pdf = new FPDF();
        \$pdf->AddPage();
        \$pdf->SetFont('Arial','B',16);
        \$pdf->Cell(0,10,'Loan Contract',0,1,'C');
        \$pdf->Ln(5);

        \$pdf->SetFont('Arial','',12);
        \$pdf->Cell(0,8,\"Client Name: {\$data['name']}\",0,1);
        \$pdf->Cell(0,8,\"NRC: {\$data['nrc']}\",0,1);
        \$pdf->Cell(0,8,\"Country: {\$data['country']}\",0,1);
        \$pdf->Ln(5);

        \$pdf->Cell(0,8,\"Loan Amount: ZMW {\$data['amount']}\",0,1);
        \$pdf->Cell(0,8,sprintf('Interest Rate: %.2f%%', \$data['interest_rate']*100),0,1);
        \$pdf->Cell(0,8,\"Due Date: {\$data['due_date']}\",0,1);
        \$pdf->Ln(10);

        \$contractText = \"By approving, you agree to repay the loan by the due date and consent to collateral liquidation on default.\";
        \$pdf->MultiCell(0,6, \$contractText);
        \$pdf->Ln(10);

        \$pdf->Cell(0,8,'Client Signature: _________________________',0,1);
        \$pdf->Cell(0,8,'Date: ' . date('Y-m-d'),0,1);

        // Save file
        \$dir = __DIR__ . '/../contracts/';
        if (!is_dir(\$dir)) mkdir(\$dir, 0755, true);
        \$fileName = \"contract_\{$loanId}.pdf\";
        \$filePath = \$dir . \$fileName;
        \$pdf->Output('F', \$filePath);

        // Record in database
        \$db->prepare(\"INSERT INTO contracts(loan_id, path) VALUES(?,?)\")
            ->execute([\$loanId, 'contracts/' . \$fileName]);
    }

    // Mark as approved when client signs
    public static function approve(\$contractId) {
        \$db = Database::getInstance()->getConnection();
        \$db->prepare(\"UPDATE contracts SET approved_at = NOW() WHERE id = ?\")
            ->execute([\$contractId]);
    }
}
