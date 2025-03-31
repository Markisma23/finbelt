<?php
require_once 'Database.php';

class Auction {
    // Get all active auctions
    public static function getActiveAuctions() {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM auctions WHERE status = 'active'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Create an auction for defaulted collateral
    public static function createAuction($loanId, $collateral, $category = 'general') {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO auctions (loan_id, collateral, category, status) VALUES (:loan_id, :collateral, :category, 'active')");
        return $stmt->execute([
            'loan_id'   => $loanId,
            'collateral'=> $collateral,
            'category'  => $category
        ]);
    }
}
