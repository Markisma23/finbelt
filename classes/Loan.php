<?php
class Loan {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function applyLoan($userId, $amount, $collateral) {
        if($amount < 1000) {
            return false;
        }
        // Set initial loan status to 'pending'
        $stmt = $this->db->prepare("INSERT INTO loans (user_id, amount, collateral, status) VALUES (?, ?, ?, 'pending')");
        return $stmt->execute([$userId, $amount, $collateral]);
    }

    public function getUserLoans($userId) {
        $stmt = $this->db->prepare("SELECT * FROM loans WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllLoans() {
        // For admin usage, get all loan applications
        $stmt = $this->db->query("SELECT loans.*, users.username FROM loans JOIN users ON loans.user_id = users.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update the status of a loan.
     * @param int $loanId Loan application identifier.
     * @param string $status New status value: 'approved', 'rejected', or 'defaulted'.
     */
    public function updateLoanStatus($loanId, $status) {
        $allowed = ['approved', 'rejected', 'defaulted'];
        if (!in_array($status, $allowed)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE loans SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $loanId]);
    }
}
?>
