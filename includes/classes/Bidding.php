<?php
require_once 'Database.php';

class Bidding {
    // Record a bid and maintain bidding history
    public static function recordBid($clientId, $auctionId, $bidAmount) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO bid_history (client_id, auction_id, bid_amount, bid_time) VALUES (:client_id, :auction_id, :bid_amount, NOW())");
        return $stmt->execute([
            'client_id'  => $clientId,
            'auction_id' => $auctionId,
            'bid_amount' => $bidAmount
        ]);
    }
    
    // Analyze bid history to return the most bid category for a client
    public static function getPreferredCategory($clientId) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT a.category, COUNT(*) as bid_count 
                               FROM bid_history bh 
                               JOIN auctions a ON bh.auction_id = a.id 
                               WHERE bh.client_id = :clientId 
                               GROUP BY a.category 
                               ORDER BY bid_count DESC 
                               LIMIT 1");
        $stmt->execute(['clientId' => $clientId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['category'] : null;
    }
}
