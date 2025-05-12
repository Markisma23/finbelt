<?php
class Auction {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function createAuction($loanId, $collateral, $startPrice, $auctionEnd = null) {
        $stmt = $this->db->prepare("INSERT INTO auctions (loan_id, collateral, start_price, current_price, auction_end) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$loanId, $collateral, $startPrice, $startPrice, $auctionEnd]);
    }

    public function getActiveAuctions() {
        $stmt = $this->db->query("SELECT * FROM auctions WHERE status = 'active'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCurrentPrice($auctionId, $price) {
        $stmt = $this->db->prepare("UPDATE auctions SET current_price = ? WHERE id = ?");
        return $stmt->execute([$price, $auctionId]);
    }

    /**
     * Close auctions that have passed their auction_end datetime.
     */
    public function closeExpiredAuctions() {
        // Retrieve active auctions that have ended.
        $stmt = $this->db->prepare("SELECT * FROM auctions WHERE status = 'active' AND auction_end IS NOT NULL AND auction_end < NOW()");
        $stmt->execute();
        $expiredAuctions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($expiredAuctions as $auction) {
            // Retrieve highest bid for this auction.
            $bidObj = new Bid();
            $highestBid = $bidObj->getHighestBid($auction['id']);
            if ($highestBid) {
                // Update the current price based on the highest bid
                $this->updateCurrentPrice($auction['id'], $highestBid['bid_amount']);
            }
            // Close the auction.
            $stmtClose = $this->db->prepare("UPDATE auctions SET status = 'closed' WHERE id = ?");
            $stmtClose->execute([$auction['id']]);
            
            // Notify the winner if there is one.
            if ($highestBid) {
                $notification = new Notification();
                $notification->addNotification($highestBid['user_id'], $auction['id'],
                    "Congratulations, you won auction #{$auction['id']} with a bid of ZMW {$highestBid['bid_amount']}.");
            }
        }
    }
}
?>
