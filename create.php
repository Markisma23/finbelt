<?php
/**
 * Finbelt Loan System
 * 
 * Directory Structure:
 *
 * /index.php
 * /config/config.php
 * /autoload.php
 * /classes/
 *     Core/
 *         Router.php
 *         Controller.php
 *         Model.php
 *         View.php
 *         Database.php
 *         Mailer.php
 *         Backup.php
 *     Entities/
 *         User.php
 *         Admin.php
 *         Loan.php
 *         Auction.php
 *         Bid.php
 *         Collateral.php
 *         Notification.php
 *     Services/
 *         AuthService.php
 *         LoanService.php
 *         AuctionService.php
 *         NotificationService.php
 *         BackupService.php
 *         TranslationService.php
 * /admin/
 *     includes/header.php
 *     includes/footer.php
 *     dashboard.php
 *     users.php
 *     loans.php
 *     auctions.php
 *     backups.php
 *     permissions.php
 * /client/
 *     includes/header.php
 *     includes/footer.php
 *     dashboard.php
 *     apply_loan.php
 *     auctions.php
 *     bid.php
 *     profile.php
 * /lang/
 *     en.php
 *     fr.php
 *     ...
 * /public/
 *     assets/css/
 *     assets/js/
 *     uploads/collateral/
 */

// index.php in root
defined('BASE_PATH') or define('BASE_PATH', __DIR__);
require BASE_PATH . '/autoload.php';

use Core\Router;

$router = new Router();
$router->dispatch();

// config/config.php
// <?php
// return [ ... ];

// autoload.php
spl_autoload_register(function ($class) {
    $file = BASE_PATH . '/classes/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// classes/Core/Database.php
namespace Core;

use PDO;

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $cfg = require BASE_PATH . '/config/config.php';
        $db = $cfg['db'];
        $dsn = "mysql:host={$db['host']};dbname={$db['name']};charset=utf8mb4";
        $this->pdo = new PDO($dsn, $db['user'], $db['pass'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}

// Database schema file: /database/schema.sql
/*
-- users table
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role ENUM('client','admin','super_admin') NOT NULL,
  username VARCHAR(50) UNIQUE NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  last_login DATETIME NULL,
  preferences JSON NULL,
  status ENUM('active','inactive') DEFAULT 'active'
);

-- permissions table
CREATE TABLE IF NOT EXISTS permissions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) UNIQUE NOT NULL,
  description TEXT
);

-- admin_permissions mapping
CREATE TABLE IF NOT EXISTS admin_permissions (
  admin_id INT NOT NULL,
  permission_id INT NOT NULL,
  PRIMARY KEY(admin_id, permission_id),
  FOREIGN KEY(admin_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY(permission_id) REFERENCES permissions(id) ON DELETE CASCADE
);

-- loans table
CREATE TABLE IF NOT EXISTS loans (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  principal DECIMAL(15,2) NOT NULL,
  interest_rate DECIMAL(5,2) NOT NULL,
  period ENUM('weekly','monthly') NOT NULL,
  collateral_id INT NULL,
  status ENUM('pending','approved','rejected','active','defaulted','closed') NOT NULL DEFAULT 'pending',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  due_date DATE NOT NULL,
  FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- collaterals table
CREATE TABLE IF NOT EXISTS collaterals (
  id INT AUTO_INCREMENT PRIMARY KEY,
  loan_id INT NOT NULL,
  filename VARCHAR(255) NOT NULL,
  uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(loan_id) REFERENCES loans(id) ON DELETE CASCADE
);

-- auctions table
CREATE TABLE IF NOT EXISTS auctions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  collateral_id INT NOT NULL,
  start_date DATETIME NOT NULL,
  end_date DATETIME NOT NULL,
  status ENUM('open','closed','sold','unsold') NOT NULL DEFAULT 'open',
  listing_fee DECIMAL(5,2) NOT NULL DEFAULT 2.00,
  FOREIGN KEY(collateral_id) REFERENCES collaterals(id) ON DELETE CASCADE
);

-- bids table
CREATE TABLE IF NOT EXISTS bids (
  id INT AUTO_INCREMENT PRIMARY KEY,
  auction_id INT NOT NULL,
  user_id INT NOT NULL,
  amount DECIMAL(15,2) NOT NULL,
  bid_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY(auction_id) REFERENCES auctions(id) ON DELETE CASCADE,
  FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- notifications table
CREATE TABLE IF NOT EXISTS notifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  type VARCHAR(50) NOT NULL,
  payload JSON NOT NULL,
  sent_at DATETIME NULL,
  status ENUM('pending','sent','failed') NOT NULL DEFAULT 'pending',
  FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);
*/

// Notes:
// - Core services (AuthService, LoanService, AuctionService, NotificationService, BackupService, TranslationService) should follow SOLID principles.
// - Email reminders (10, 5, 2, 1 days before due date) and transactional emails are handled via NotificationService + cron.
// - Super-admins (role 'super_admin') can manage admin users and assign permissions.
// - File uploads (collateral images) saved under /public/uploads/collateral with proper validation.
// - i18n via TranslationService loading /lang/*.php arrays.

?>
