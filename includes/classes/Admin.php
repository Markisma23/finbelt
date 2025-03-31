<?php
require_once 'User.php';
require_once 'Database.php';
require_once 'EmailNotification.php'; // Ensure the email class is available

class Admin extends User {
    public $permissions; // 'all' or 'limited'
    
    public function __construct($id, $name, $email, $password, $permissions = 'all') {
        parent::__construct($id, $name, $email, $password, 'admin');
        $this->permissions = $permissions;
    }
    
    // Create a new admin account with configurable permission levels
    public function createAdminAccount($name, $email, $password, $permissions = 'limited') {
        if (!$this->canCreateAdmin()) {
            throw new Exception("You do not have permission to create an admin account.");
        }
        $pdo = Database::getInstance();
        $hashedPassword = self::hashPassword($password);
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, permissions) VALUES (:name, :email, :password, 'admin', :permissions)");
        return $stmt->execute([
            'name'        => $name,
            'email'       => $email,
            'password'    => $hashedPassword,
            'permissions' => $permissions
        ]);
    }
    
    // Check if this admin has full privileges to create other admins
    private function canCreateAdmin() {
        return $this->permissions === 'all';
    }
    
    // Approve a loan application and notify the client via email
    public function approveLoan($loanId) {
        $pdo = Database::getInstance();
        
        // Retrieve the loan record to get the client ID
        $stmt = $pdo->prepare("SELECT client_id FROM loans WHERE id = :loanId");
        $stmt->execute(['loanId' => $loanId]);
        $loan = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$loan) {
            throw new Exception("Loan not found");
        }
        
        // Approve the loan
        $stmt = $pdo->prepare("UPDATE loans SET status = 'approved' WHERE id = :loanId");
        $result = $stmt->execute(['loanId' => $loanId]);
        
        if ($result) {
            // Retrieve the client's email address
            $stmtClient = $pdo->prepare("SELECT email, name FROM users WHERE id = :clientId");
            $stmtClient->execute(['clientId' => $loan['client_id']]);
            $client = $stmtClient->fetch(PDO::FETCH_ASSOC);
            if ($client) {
                // Send an email notification to the client
                $emailNotifier = new EmailNotification();
                $subject = "Your Loan Application Has Been Approved!";
                $body = "Dear " . htmlspecialchars($client['name']) . ",<br><br>Your loan application (ID: " . $loanId . ") has been approved. Congratulations!<br><br>Best regards,<br>Finbelt Microfinance";
                $emailNotifier->sendEmail($client['email'], $subject, $body);
            }
        }
        return $result;
    }
}
