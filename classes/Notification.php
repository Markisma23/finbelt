<?php
namespace App;
use PDO;
class Notification {
    // Send generic email
    public static function sendEmail($to, $subject, $message) {
        $headers = 'From: '.\Config\Config::MAIL_FROM ."\r\n" .
                   'Reply-To: '.\Config\Config::MAIL_FROM ."\r\n" .
                   'X-Mailer: PHP/' . phpversion();
        mail($to, $subject, $message, $headers);
    }

    // Called on new loan application
    public static function sendEmailForLoan($clientId, $loanId, $subject) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT email FROM users WHERE id=(SELECT client_id FROM loans WHERE id=?)");
        $stmt->execute([$loanId]); $email = $stmt->fetchColumn();
        $body = "Your loan application (#{$loanId}) has been received and is pending review.";
        self::sendEmail($email, $subject, $body);
    }

    // Send reminder email for upcoming due date
    public static function sendReminder($loan) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT email FROM users WHERE id=?");
        $stmt->execute([$loan['client_id']]); $email = $stmt->fetchColumn();
        $daysLeft = (new \DateTime($loan['due_date']))->diff(new \DateTime())->days;
        $subject = "Loan #{$loan['id']} due in {$daysLeft} days";
        \$body = "Dear Client,\nYour loan #{$loan['id']} for ZMW {$loan['amount']} is due on {$loan['due_date']} (in {$daysLeft} days). Please ensure repayment to avoid penalties.";
        self::sendEmail($email, $subject, \$body);
    }

    // Scheduler to be run daily via cron
    public static function scheduleReminders() {
        $db = Database::getInstance()->getConnection();
        $today = new \DateTime();
        foreach (\Config\Config::REMINDER_SCHEDULE as $daysBefore) {
            $date = \$today->modify("+{$daysBefore} days")->format('Y-m-d');
            // reset DateTime for next iteration
            \$today = new \DateTime();
            \$stmt = \$db->prepare("SELECT * FROM loans WHERE due_date=? AND status='approved'");
            \$stmt->execute([\$date]);
            \$loans = \$stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach (\$loans as \$loan) {
                self::sendReminder(\$loan);
            }
        }
    }
}
?>