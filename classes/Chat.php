<?php
class Chat {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Send a chat message from one user to another.
     *
     * @param int $senderId
     * @param int $receiverId
     * @param string $message
     * @return bool
     */
    public function sendMessage($senderId, $receiverId, $message) {
        $stmt = $this->db->prepare("INSERT INTO chat_messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        return $stmt->execute([$senderId, $receiverId, $message]);
    }
    
    /**
     * Retrieve chat messages between two users, optionally since a specific time.
     *
     * @param int $user1
     * @param int $user2
     * @param string|null $since Optional datetime to filter messages.
     * @return array
     */
    public function getMessages($user1, $user2, $since = null) {
        $query = "SELECT cm.*, u.username as sender_name 
                  FROM chat_messages cm 
                  JOIN users u ON cm.sender_id = u.id
                  WHERE ((sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?))";
        $params = [$user1, $user2, $user2, $user1];
        if ($since !== null) {
            $query .= " AND created_at > ?";
            $params[] = $since;
        }
        $query .= " ORDER BY created_at ASC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Retrieve the most recent chat messages for a given conversation.
     * If $receiverId is 0 (or a specific flag), then it can be a broadcast or support chat.
     *
     * @param int $userId The logged-in user ID.
     * @param int $otherUserId The other party.
     * @return array
     */
    public function getRecentMessages($userId, $otherUserId) {
        return $this->getMessages($userId, $otherUserId);
    }
}
?>
