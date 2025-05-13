<?php

namespace App;
use PDO;
class Loan {
    public static function getByClient($clientId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM loans WHERE client_id=? ORDER BY due_date ASC");
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getPending() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT l.*, u.email FROM loans l JOIN users u ON l.client_id=u.id WHERE l.status='pending'");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($clientId, $amount, $term, $collateralPath) {
        $db = Database::getInstance()->getConnection();
        $rate = ($term==='monthly') ? \Config\Config::INTEREST_MONTHLY : \Config\Config::INTEREST_WEEKLY;
        $dueDate = ($term==='monthly') ? date('Y-m-d', strtotime('+1 month')) : date('Y-m-d', strtotime('+1 week'));
        $stmt = $db->prepare("INSERT INTO loans(client_id,amount,term_type,interest_rate,status,due_date,collateral_path)
            VALUES(?,?,?,?,?,?,?)");
        $stmt->execute([$clientId, $amount, $term, $rate, 'pending', $dueDate, $collateralPath]);
        $loanId = $db->lastInsertId();
        Contract::generate($clientId, $loanId);
        Notification::sendEmailForLoan($clientId, $loanId, 'Loan Application Received');
        return $loanId;
    }
    public static function approve($loanId, $adminId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE loans SET status='approved', approved_by=?, approved_at=NOW() WHERE id=?");
        $stmt->execute([$adminId, $loanId]);
        Notification::sendLoanStatus($loanId, 'approved');
    }
    public static function reject($loanId, $adminId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("UPDATE loans SET status='rejected', approved_by=?, approved_at=NOW() WHERE id=?");
        $stmt->execute([$adminId, $loanId]);
        Notification::sendLoanStatus($loanId, 'rejected');
    }
}
?>