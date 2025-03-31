<?php
require_once 'User.php';
require_once 'Bidding.php';

class Client extends User {
    public function __construct($id, $name, $email, $password) {
        parent::__construct($id, $name, $email, $password, 'client');
    }
    
    // Register a new client account
    public static function register($name, $email, $password) {
        $pdo = Database::getInstance();
        $hashedPassword = self::hashPassword($password);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'client')");
        return $stmt->execute(['name' => $name, 'email' => $email, 'password' => $hashedPassword]);
    }
    
    // Apply for a loan; minimum amount is ZMW 1000
    public function applyForLoan($amount, $collateralDetails) {
        if ($amount < 1000) {
            throw new Exception("Minimum loan amount is ZMW 1000.");
        }
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO loans (client_id, amount, collateral, status) VALUES (:client_id, :amount, :collateral, 'pending')");
        return $stmt->execute([
            'client_id'  => $this->id,
            'amount'     => $amount,
            'collateral' => $collateralDetails
        ]);
    }
    
    // Place a bid on an auction and record bidding behavior
    public function placeBid($auctionId, $bidAmount) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO bids (auction_id, client_id, bid_amount) VALUES (:auction_id, :client_id, :bid_amount)");
        $stmt->execute([
            'auction_id' => $auctionId,
            'client_id'  => $this->id,
            'bid_amount' => $bidAmount
        ]);
        Bidding::recordBid($this->id, $auctionId, $bidAmount);
        return true;
    }
}
