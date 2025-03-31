<?php
require_once 'Bidding.php';
require_once 'Database.php';

class Notification {
    // Generate notifications based on the client's bidding preferences
    public static function notifyPreferredAuctions($clientId) {
        $preferredCategory = Bidding::getPreferredCategory($clientId);
        if ($preferredCategory) {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("SELECT * FROM auctions WHERE category = :category AND status = 'active'");
            $stmt->execute(['category' => $preferredCategory]);
            $auctions = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return "Your preferred auction category is " . $preferredCategory . ". Check out these listings: " . json_encode($auctions);
        }
        return "No preferred auction category determined yet.";
    }
}
