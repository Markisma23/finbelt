<?php
require_once 'Database.php';

class Loan {
    public static function getAllLoans() {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM loans");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function getLoanById($loanId) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM loans WHERE id = :loanId");
        $stmt->execute(['loanId' => $loanId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public static function applyLoan($clientId, $amount, $collateralDetails) {
        if ($amount < 1000) {
            throw new Exception("Minimum loan amount is ZMW 1000.");
        }
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO loans (client_id, amount, collateral, status) VALUES (:client_id, :amount, :collateral, 'pending')");
        return $stmt->execute([
            'client_id'  => $clientId,
            'amount'     => $amount,
            'collateral' => $collateralDetails
        ]);
    }
}
