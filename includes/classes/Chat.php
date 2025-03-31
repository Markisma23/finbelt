<?php
require_once 'Database.php';

class Chat {
    /**
     * Send a chat message from a sender to a recipient.
     *
     * @param int $senderId The user sending the message.
     * @param int $recipientId The user receiving the message.
     * @param string $message The text message.
     * @return bool True if the message was recorded.
     */
    public static function sendMessage($senderId, $recipientId, $message) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO chat_messages (sender_id, recipient_id, message) VALUES (:sender_id, :recipient_id, :message)");
        return $stmt->execute([
            'sender_id'    => $senderId,
            'recipient_id' => $recipientId,
            'message'      => $message
        ]);
    }

    /**
     * Retrieve chat messages between two users.
     *
     * @param int $userId1
     * @param int $userId2
     * @return array List of messages ordered by created_at.
     */
    public static function getMessagesBetween($userId1, $userId2) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM chat_messages 
                               WHERE (sender_id = :user1 AND recipient_id = :user2)
                                  OR (sender_id = :user2 AND recipient_id = :user1)
                               ORDER BY created_at ASC");
        $stmt->execute(['user1' => $userId1, 'user2' => $userId2]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Mark all messages between two users as read.
     *
     * @param int $senderId
     * @param int $recipientId
     * @return bool
     */
    public static function markMessagesRead($senderId, $recipientId) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("UPDATE chat_messages SET is_read = 1 
                               WHERE sender_id = :senderId AND recipient_id = :recipientId");
        return $stmt->execute(['senderId' => $senderId, 'recipientId' => $recipientId]);
    }
}
