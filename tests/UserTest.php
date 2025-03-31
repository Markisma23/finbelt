<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../includes/classes/User.php';
require_once __DIR__ . '/../includes/classes/Database.php';

// Before running tests, you may need to adjust your database connection
// or use a separate testing database to prevent conflicts.

class UserTest extends TestCase {

    // Test for successful login (assumes test user exists in the database)
    public function testSuccessfulLogin() {
        $email = 'testuser@example.com';
        $password = 'password123';

        // Create test user if not exists (for testing only)
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            // Register a new client user for testing
            require_once __DIR__ . '/../includes/classes/Client.php';
            Client::register('Test User', $email, $password);
        }

        // Attempt to log in
        $userInstance = User::login($email, $password);
        $this->assertNotFalse($userInstance, "Login should succeed for valid credentials");
        $this->assertEquals($email, $userInstance->email, "Returned user email should match input");
    }

    // Test for failed login with invalid credentials
    public function testFailedLogin() {
        $email = 'nonexistent@example.com';
        $password = 'wrongpassword';
        $userInstance = User::login($email, $password);
        $this->assertFalse($userInstance, "Login should fail for invalid credentials");
    }
}
