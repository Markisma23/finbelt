<?php
class Feedback {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Submit new feedback.
     *
     * @param int $userId The ID of the user submitting feedback.
     * @param int $rating The rating value.
     * @param string $comments Additional comments.
     * @return bool|string Returns true on success, or an error message.
     */
    public function submitFeedback($userId, $rating, $comments) {
        // Basic validation: Ensure rating is between 1 and 5.
        if ($rating < 1 || $rating > 5) {
            return "Rating must be between 1 and 5.";
        }
        $stmt = $this->db->prepare("INSERT INTO feedback (user_id, rating, comments) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $rating, $comments]) ? true : "Failed to submit feedback.";
    }

    /**
     * Retrieve all feedback entries, optionally filtered by status.
     *
     * @param string|null $status Filter by status if provided.
     * @return array
     */
    public function getFeedback($status = null) {
        $query = "SELECT f.*, u.username FROM feedback f JOIN users u ON f.user_id = u.id";
        $params = [];
        if ($status !== null) {
            $query .= " WHERE f.status = ?";
            $params[] = $status;
        }
        $query .= " ORDER BY f.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update the status of a feedback entry.
     *
     * @param int $feedbackId
     * @param string $newStatus Should be one of 'new', 'reviewed', or 'responded'.
     * @return bool
     */
    public function updateFeedbackStatus($feedbackId, $newStatus) {
        $allowed = ['new', 'reviewed', 'responded'];
        if (!in_array($newStatus, $allowed)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE feedback SET status = ? WHERE id = ?");
        return $stmt->execute([$newStatus, $feedbackId]);
    }
}
?>
