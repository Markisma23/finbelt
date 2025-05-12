<?php
class AuditLog {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Record an audit log event.
     *
     * @param int|null $userId The ID of the user who performed the action, if applicable.
     * @param string $eventType A short title for the event (e.g., 'payment', 'loan_default').
     * @param string $description A detailed description of the event.
     * @param string|null $ipAddress Optional IP address of the user.
     * @return bool
     */
    public function logEvent($userId, $eventType, $description, $ipAddress = null) {
        $stmt = $this->db->prepare("INSERT INTO audit_logs (user_id, event_type, description, ip_address) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$userId, $eventType, $description, $ipAddress]);
    }
    
    /**
     * Retrieve audit logs with optional filtering.
     *
     * @param array $filters Array of filters (e.g., ['user_id' => 1, 'event_type' => 'payment']).
     * @return array
     */
    public function getLogs($filters = []) {
        $query = "SELECT a.*, u.username FROM audit_logs a LEFT JOIN users u ON a.user_id = u.id ";
        $conditions = [];
        $params = [];
        if (!empty($filters)) {
            foreach ($filters as $field => $value) {
                $conditions[] = "a.$field = ?";
                $params[] = $value;
            }
            $query .= "WHERE " . implode(" AND ", $conditions);
        }
        $query .= " ORDER BY a.created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
