<?php
require_once __DIR__ . '/../bootstrap.php';
use App\Database, App\Notification;
\$db = Database::getInstance()->getConnection();
\$stmt = \$db->prepare("SELECT * FROM auctions WHERE status='open' AND end_date<=NOW()");
\$stmt->execute(); \$aucs = \$stmt->fetchAll(PDO::FETCH_ASSOC);
foreach (\$aucs as \$a) {
    \$db->prepare("UPDATE auctions SET status='closed' WHERE id=?")->execute([\$a['id']]);
    \$stmt2 = \$db->prepare("SELECT client_id,amount FROM bids WHERE auction_id=? ORDER BY amount DESC LIMIT 1");
    \$stmt2->execute([\$a['id']]); \$win = \$stmt2->fetch(PDO::FETCH_ASSOC);
    if (\$win) {
        \$stmt3 = \$db->prepare("SELECT email FROM users WHERE id=?"); \$stmt3->execute([\$win['client_id']]); \$email = \$stmt3->fetchColumn();
        Notification::sendEmail(\$email, "You won auction #{\$a['id']}", "Congratulations! You’re the high bidder at ZMW {\$win['amount']}. Please arrange pickup.");
    } else {
        // No bids — liquidate collateral
    $auction = new Auction($a['id']);
    $auction->liquidate();
    }
}
?>