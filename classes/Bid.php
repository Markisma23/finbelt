<?php
class Bid {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function placeBid($auctionId, $userId, $bidAmount) {
        $stmt = $this->db->prepare("INSERT INTO bids (auction_id, user_id, bid_amount) VALUES (?, ?, ?)");
        if ($stmt->execute([$auctionId, $userId, $bidAmount])) {
            // Update auction current price if applicable.
            $auction = new Auction();
            $auction->updateCurrentPrice($auctionId, $bidAmount);
            // Add notification for bid placement.
            $notification = new Notification();
            $notification->addNotification($userId, $auctionId, "Your bid of ZMW $bidAmount was placed on auction #$auctionId.");
            return true;
        }
        return false;
    }

    public function getUserBids($userId) {
        $stmt = $this->db->prepare("SELECT * FROM bids WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getHighestBid($auctionId) {
        $stmt = $this->db->prepare("SELECT * FROM bids WHERE auction_id = ? ORDER BY bid_amount DESC LIMIT 1");
        $stmt->execute([$auctionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
