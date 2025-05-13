<?php
namespace App;
use PDO;
class Auction {
    public static function getRecommendations($clientId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            "SELECT a.* FROM auctions a
             JOIN bids b ON b.auction_id=a.id
             WHERE b.client_id=?
             GROUP BY a.category
             ORDER BY COUNT(*) DESC LIMIT 5"
        );
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function placeBid($clientId,$amt) { /*...*/ }
}
?>