<?php
// Include PHPMailer classes (adjust the paths if installed via Composer)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php'; // Assuming Composer autoload

class EmailNotification {
    private $mail;
    
    public function __construct() {
        $this->mail = new PHPMailer(true);
        // Server settings
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.example.com'; // Replace with your SMTP host
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'your_email@example.com'; // Your SMTP username
        $this->mail->Password   = 'your_email_password';      // Your SMTP password
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = 587; // Typically 587 for TLS
        
        // Sender info
        $this->mail->setFrom('your_email@example.com', 'Finbelt Microfinance');
    }
    
    /**
     * Send an email to a recipient.
     *
     * @param string $to Recipient email address
     * @param string $subject Email subject
     * @param string $body HTML body of the email
     * @return bool Returns true if sent successfully, false otherwise.
     */
    public function sendEmail($to, $subject, $body) {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->isHTML(true);
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            // Optionally, log the error using the Logger
            return false;
        }
    }
}
