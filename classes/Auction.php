<?php 

namespace App;
use PDO;
class Auction {
    public $id, $loan_id, $item_name, $image_path, $start_date, $end_date, $status;

    public function __construct($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM auctions WHERE id=?");
        $stmt->execute([$id]); $a = $stmt->fetch(PDO::FETCH_ASSOC);
        foreach ($a as $k => $v) \$this->\$k = $v;
    }

    public static function listAll() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT a.*, u.email AS owner FROM auctions a JOIN loans l ON a.loan_id=l.id JOIN users u ON l.client_id=u.id ORDER BY a.start_date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listOpen() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT a.*, (SELECT MAX(amount) FROM bids b WHERE b.auction_id=a.id) AS current_bid FROM auctions a WHERE a.status='open' AND NOW() BETWEEN a.start_date AND a.end_date");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function placeBid($clientId, $amount) {
        $db = Database::getInstance()->getConnection();
        // Ensure open
        if ($this->status !== 'open') throw new \Exception('Auction closed');
        // Record bid
        $stmt = $db->prepare("INSERT INTO bids(auction_id,client_id,amount) VALUES(?,?,?)");
        $stmt->execute([$this->id, $clientId, $amount]);
        // Notify tracking
        Notification::notifyBidTracking($clientId, $this->id);
    }

    public static function getRecommendations($clientId) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            "SELECT a.*, MAX(b.amount) AS current_bid
             FROM auctions a
             JOIN bids b ON b.auction_id=a.id
             WHERE b.client_id=?
             GROUP BY a.id
             ORDER BY COUNT(*) DESC LIMIT 5"
        );
        $stmt->execute([$clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function liquidate(): void
{
    $db = Database::getInstance()->getConnection();

    // 1) Fetch collateral path from loan
    $stmt = $db->prepare("
        SELECT collateral_path 
        FROM loans 
        WHERE id = ?
    ");
    $stmt->execute([$this->loan_id]);
    $path = $stmt->fetchColumn();

    // 2) Move collateral to 'inventory' for forced sale
    $invDir = __DIR__ . '/../inventory/';
    if (!is_dir($invDir)) mkdir($invDir, 0755, true);
    $fileName = basename($path);
    rename(__DIR__ . '/../' . $path, $invDir . $fileName);

    // 3) Mark the loan as liquidated
    $db->prepare("
        UPDATE loans 
        SET status = 'liquidated' 
        WHERE id = ?
    ")->execute([$this->loan_id]);

    // 4) Notify the original borrower
    Notification::sendEmail(
        currentUser()->email,
        "Collateral Liquidated for Loan #{$this->loan_id}",
        "Your collateral has been moved to inventory for forced sale due to default."
    );
}
}
?>