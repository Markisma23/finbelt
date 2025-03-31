<?php
require_once 'Database.php';

class Repurchase {
    /**
     * Submit a repurchase request.
     *
     * @param int $auctionId Auction identifier.
     * @param int $clientId Client making the repurchase request.
     * @param float $offeredPrice The repurchase offer.
     * @return bool
     */
    public static function submitRequest($auctionId, $clientId, $offeredPrice) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO repurchase_requests (auction_id, client_id, offered_price) 
                               VALUES (:auction_id, :client_id, :offered_price)");
        return $stmt->execute([
            'auction_id'   => $auctionId,
            'client_id'    => $clientId,
            'offered_price'=> $offeredPrice
        ]);
    }
    
    /**
     * Get all repurchase requests.
     *
     * @return array
     */
    public static function getAllRequests() {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM repurchase_requests ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Update the status of a repurchase request.
     *
     * @param int $requestId
     * @param string $status ('accepted' or 'declined')
     * @return bool
     */
    public static function updateStatus($requestId, $status) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("UPDATE repurchase_requests SET status = :status WHERE id = :id");
        return $stmt->execute([
            'status' => $status,
            'id'     => $requestId
        ]);
    }
    
    /**
     * Get repurchase requests for a specific client.
     *
     * @param int $clientId
     * @return array
     */
    public static function getRequestsForClient($clientId) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM repurchase_requests WHERE client_id = :client_id ORDER BY created_at DESC");
        $stmt->execute(['client_id' => $clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
