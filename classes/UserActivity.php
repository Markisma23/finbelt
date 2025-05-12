<?php
class UserActivity {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Log an activity event.
     *
     * @param int|null $userId ID of the user, if available.
     * @param string $eventType The type of event (e.g., 'page_view', 'click').
     * @param string|null $pageUrl The URL or identifier of the page.
     * @param array|null $additionalData Optional extra data as an associative array.
     * @param string|null $ipAddress The IP address of the user.
     * @return bool
     */
    public function logEvent($userId, $eventType, $pageUrl = null, $additionalData = null, $ipAddress = null) {
        $jsonData = $additionalData ? json_encode($additionalData) : null;
        // If IP address is not provided, attempt to use server variable.
        if (!$ipAddress && !empty($_SERVER['REMOTE_ADDR'])) {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        $stmt = $this->db->prepare("INSERT INTO user_activity_logs (user_id, event_type, page_url, ip_address, additional_data) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$userId, $eventType, $pageUrl, $ipAddress, $jsonData]);
    }
    
    /**
     * Retrieve recent activity logs.
     *
     * @param int $limit Number of records to retrieve.
     * @return array
     */
    public function getRecentActivity($limit = 50) {
        $stmt = $this->db->prepare("SELECT aal.*, u.username FROM user_activity_logs aal LEFT JOIN users u ON aal.user_id = u.id ORDER BY aal.created_at DESC LIMIT ?");
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get aggregated page view counts.
     *
     * @return array Array of page_url and count.
     */
    public function getPageViewAggregates() {
        $stmt = $this->db->query("SELECT page_url, COUNT(*) as total_views FROM user_activity_logs WHERE event_type = 'page_view' GROUP BY page_url ORDER BY total_views DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get aggregated event counts by type.
     *
     * @return array Array of event_type and count.
     */
    public function getEventTypeAggregates() {
        $stmt = $this->db->query("SELECT event_type, COUNT(*) as total FROM user_activity_logs GROUP BY event_type ORDER BY total DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
