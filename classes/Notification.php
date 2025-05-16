<?php
namespace App;
use PDO;
class Notification {
    public static function sendEmail(\$to, \$subject, \$message) {
        \$headers = 'From: ' . Config\Config::MAIL_FROM . "\r\n" .
                   'Reply-To: ' . Config\Config::MAIL_FROM . "\r\n" .
                   'X-Mailer: PHP/' . phpversion();
        mail(\$to, \$subject, \$message, \$headers);
    }
    public static function sendEmailForLoan(\$clientId, \$loanId, \$subject) {
        \$db = Database::getInstance()->getConnection();
        \$stmt = \$db->prepare("SELECT email FROM users WHERE id=(SELECT client_id FROM loans WHERE id=?)");
        \$stmt->execute([\$loanId]); \$email = \$stmt->fetchColumn();
        \$body = "Your loan application (#{\$loanId}) has been received and is pending review.";
        self::sendEmail(\$email, \$subject, \$body);
    }
    public static function sendLoanStatus(\$loanId, \$status) {
        \$db = Database::getInstance()->getConnection();
        \$stmt = \$db->prepare("SELECT u.email, l.amount FROM loans l JOIN users u ON l.client_id=u.id WHERE l.id=?");
        \$stmt->execute([\$loanId]); list(\$email, \$amount) = \$stmt->fetch(PDO::FETCH_NUM);
        \$subject = "Loan #{\$loanId} has been {\$status}";
        \$body = "Your loan application (#{\$loanId}) for ZMW {\$amount} has been {\$status}.";
        self::sendEmail(\$email, \$subject, \$body);
    }
    public static function sendReminder(\$loan) {
        \$db = Database::getInstance()->getConnection();
        \$stmt = \$db->prepare("SELECT email FROM users WHERE id=?");
        \$stmt->execute([\$loan['client_id']]); \$email = \$stmt->fetchColumn();
        \$daysLeft = (new \DateTime(\$loan['due_date']))->diff(new \DateTime())->days;
        \$subject = "Loan #{\$loan['id']} due in {\$daysLeft} days";
        \$body = "Dear Client,\nYour loan #{\$loan['id']} for ZMW {\$loan['amount']} is due on {\$loan['due_date']} (in {\$daysLeft} days). Please ensure repayment to avoid penalties.";
        self::sendEmail(\$email, \$subject, \$body);
    }
    public static function notifyBidTracking(\$clientId, \$auctionId) {
        \$db = Database::getInstance()->getConnection();
        // Record bid history
        \$stmt = \$db->prepare("INSERT INTO bid_history (client_id, auction_id, bid_at) VALUES (?,?,NOW())");
        \$stmt->execute([\$clientId, \$auctionId]);
        // Track category preferences
        \$stmt = \$db->prepare("SELECT category FROM auctions WHERE id=?");
        \$stmt->execute([\$auctionId]); \$category = \$stmt->fetchColumn();
        \$stmt = \$db->prepare(
            "INSERT INTO category_preferences (client_id, category, count) VALUES (?,?,1) \
             ON DUPLICATE KEY UPDATE count = count + 1"
        );
        \$stmt->execute([\$clientId, \$category]);
        // Send confirmation email
        \$stmt = \$db->prepare("SELECT email FROM users WHERE id=?");
        \$stmt->execute([\$clientId]); \$email = \$stmt->fetchColumn();
        \$subject = "Thanks for your bid!";
        \$body = "We’ve recorded your bid on auction #{\$auctionId} (category: {\$category}). Good luck!";
        self::sendEmail(\$email, \$subject, \$body);
    }
    public static function scheduleReminders() {
        \$db = Database::getInstance()->getConnection();
        \$base = new \DateTime();
        foreach (Config\Config::REMINDER_SCHEDULE as \$daysBefore) {
            \$d = (clone \$base)->modify("+{\$daysBefore} days")->format('Y-m-d');
            \$stmt = \$db->prepare("SELECT * FROM loans WHERE due_date=? AND status='approved'");
            \$stmt->execute([\$d]);
            foreach (\$stmt->fetchAll(PDO::FETCH_ASSOC) as \$loan) {
                self::sendReminder(\$loan);
            }
        }
    }
}
?>