<?php
class SupportTicket {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Create a new support ticket.
     *
     * @param int $userId
     * @param string $subject
     * @param string $message
     * @return bool
     */
    public function createTicket($userId, $subject, $message) {
        $stmt = $this->db->prepare("INSERT INTO support_tickets (user_id, subject, message) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $subject, $message]);
    }

    /**
     * Retrieve all tickets for a given user.
     *
     * @param int $userId
     * @return array
     */
    public function getTicketsByUser($userId) {
        $stmt = $this->db->prepare("SELECT * FROM support_tickets WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve all tickets (for admin use).
     *
     * @return array
     */
    public function getAllTickets() {
        $stmt = $this->db->query("SELECT t.*, u.username FROM support_tickets t JOIN users u ON t.user_id = u.id ORDER BY t.created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieve a single ticket by ticket ID.
     *
     * @param int $ticketId
     * @return array|false
     */
    public function getTicket($ticketId) {
        $stmt = $this->db->prepare("SELECT * FROM support_tickets WHERE id = ?");
        $stmt->execute([$ticketId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update the status of a support ticket.
     *
     * @param int $ticketId
     * @param string $status Allowed values: 'open', 'in_progress', 'closed'
     * @return bool
     */
    public function updateTicketStatus($ticketId, $status) {
        $allowed = ['open', 'in_progress', 'closed'];
        if (!in_array($status, $allowed)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE support_tickets SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $ticketId]);
    }

    /**
     * Add a reply to an existing ticket.
     *
     * @param int $ticketId
     * @param int $userId
     * @param string $message
     * @return bool
     */
    public function addReply($ticketId, $userId, $message) {
        $stmt = $this->db->prepare("INSERT INTO ticket_replies (ticket_id, user_id, message) VALUES (?, ?, ?)");
        return $stmt->execute([$ticketId, $userId, $message]);
    }

    /**
     * Retrieve all replies for a given ticket.
     *
     * @param int $ticketId
     * @return array
     */
    public function getReplies($ticketId) {
        $stmt = $this->db->prepare("SELECT r.*, u.username FROM ticket_replies r JOIN users u ON r.user_id = u.id WHERE r.ticket_id = ? ORDER BY r.created_at ASC");
        $stmt->execute([$ticketId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
