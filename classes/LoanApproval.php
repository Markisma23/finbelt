<?php
class LoanApproval {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Record an approval decision for a loan.
     *
     * @param int $loanId The ID of the loan.
     * @param int $approverId The admin/approver’s user ID.
     * @param string $status The approval decision: 'approved', 'rejected', 'on_hold'.
     * @param string|null $comment Optional comment explaining the decision.
     * @return bool
     */
    public function recordApproval($loanId, $approverId, $status, $comment = null) {
        $allowed = ['approved', 'rejected', 'on_hold'];
        if (!in_array($status, $allowed)) {
            return false;
        }
        $stmt = $this->db->prepare("INSERT INTO loan_approvals (loan_id, approver_id, status, comment) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$loanId, $approverId, $status, $comment]);

        // Optionally, update the loan status in the loans table.
        if ($result) {
            $loanStmt = $this->db->prepare("UPDATE loans SET status = ? WHERE id = ?");
            $loanStmt->execute([$status, $loanId]);
        }
        return $result;
    }

    /**
     * Retrieve the approval history for a given loan.
     *
     * @param int $loanId
     * @return array
     */
    public function getApprovalHistory($loanId) {
        $stmt = $this->db->prepare("SELECT la.*, u.username as approver_name FROM loan_approvals la JOIN users u ON la.approver_id = u.id WHERE loan_id = ? ORDER BY la.created_at ASC");
        $stmt->execute([$loanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve all loans pending approval.
     *
     * @return array
     */
    public function getPendingLoans() {
        // Assuming a loan is pending if its status is 'pending'.
        $stmt = $this->db->prepare("SELECT l.*, u.username as applicant FROM loans l JOIN users u ON l.user_id = u.id WHERE l.status = 'pending' ORDER BY l.applied_at ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
