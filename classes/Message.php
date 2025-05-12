<?php
class Message {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function sendMessage($senderId, $receiverId, $messageText) {
        $stmt = $this->db->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        return $stmt->execute([$senderId, $receiverId, $messageText]);
    }
    
    public function getUserMessages($userId) {
        $stmt = $this->db->prepare("SELECT m.*, u.username as sender_username FROM messages m JOIN users u ON m.sender_id = u.id WHERE receiver_id = ? ORDER BY sent_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function markAsRead($messageId) {
        $stmt = $this->db->prepare("UPDATE messages SET is_read = 1 WHERE id = ?");
        return $stmt->execute([$messageId]);
    }
}
?>
