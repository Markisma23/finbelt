<?php
class LoanReschedule {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Create a new loan reschedule request.
     *
     * @param int $loanId The loan ID.
     * @param int $newTerm The new term in months.
     * @param float $newInterestRate The proposed monthly interest rate.
     * @param string $reason The reason for rescheduling.
     * @return bool|string True on success, or an error message.
     */
    public function requestReschedule($loanId, $newTerm, $newInterestRate, $reason) {
        // Additional validation may be added here.
        $stmt = $this->db->prepare("INSERT INTO loan_reschedules (loan_id, new_term, new_interest_rate, reason) VALUES (?, ?, ?, ?)");
        $result = $stmt->execute([$loanId, $newTerm, $newInterestRate, $reason]);
        return $result ? true : "Failed to submit reschedule request.";
    }

    /**
     * Retrieve a reschedule request for a given loan.
     *
     * @param int $loanId
     * @return array|false
     */
    public function getRescheduleByLoan($loanId) {
        $stmt = $this->db->prepare("SELECT * FROM loan_reschedules WHERE loan_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$loanId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve all reschedule requests (optionally filtered by status).
     *
     * @param string|null $status Optional: 'pending', 'approved', or 'rejected'
     * @return array
     */
    public function getAllRescheduleRequests($status = null) {
        $query = "SELECT lr.*, l.amount as loan_amount, u.username 
                  FROM loan_reschedules lr
                  JOIN loans l ON lr.loan_id = l.id
                  JOIN users u ON l.user_id = u.id";
        $params = [];
        if ($status !== null) {
            $query .= " WHERE lr.status = ?";
            $params[] = $status;
        }
        $query .= " ORDER BY lr.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update the status of a reschedule request.
     *
     * @param int $requestId
     * @param string $newStatus 'approved' or 'rejected'
     * @return bool
     */
    public function updateRescheduleStatus($requestId, $newStatus) {
        $allowed = ['approved', 'rejected'];
        if (!in_array($newStatus, $allowed)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE loan_reschedules SET status = ? WHERE id = ?");
        return $stmt->execute([$newStatus, $requestId]);
    }
}
?>
