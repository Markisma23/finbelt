<?php
class Notification {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function addNotification($userId, $auctionId, $message) {
        $stmt = $this->db->prepare("INSERT INTO notifications (user_id, auction_id, message) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $auctionId, $message]);
        // Optional: Send email notification
        $this->sendEmailNotification($userId, $message);
        return true;
    }

    public function getNotifications($userId) {
        $stmt = $this->db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function analyzeBidHistory($userId) {
        $bid = new Bid();
        $bids = $bid->getUserBids($userId);
        $categories = [];
        foreach ($bids as $b) {
            // For demo purposes assume we extract a category from the auction id.
            $category = 'general';
            if (!isset($categories[$category])) {
                $categories[$category] = 0;
            }
            $categories[$category]++;
        }
        arsort($categories);
        $mostBidCategory = key($categories);
        $this->addNotification($userId, null, "Based on your bidding activity, auction listings in the '{$mostBidCategory}' category have been prioritized.");
    }

    // Stub for email notification; in practice, use a robust mailer.
    private function sendEmailNotification($userId, $message) {
        // In a production environment, retrieve email from user table.
        $userObj = new User();
        $user = $userObj->getUserById($userId);
        if ($user && isset($user['email'])) {
            $to = $user['email'];
            $subject = "Notification from Finbelt Microfinance";
            $body = $message;
            $headers = "From: no-reply@finbeltmicrofinance.com\r\n" .
                       "Content-Type: text/plain; charset=utf-8";
            // Uncomment in production:
            // mail($to, $subject, $body, $headers);
        }
    }
}
?>
