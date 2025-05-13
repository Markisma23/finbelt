<?php
namespace App;
use PDO;
class Database {
    private static $instance;
    private $pdo;
    private function __construct() {
        \$dsn = "mysql:host=".\Config\Config::DB_HOST.";dbname=".\Config\Config::DB_NAME;
        \$this->pdo = new PDO(\$dsn, \Config\Config::DB_USER, \Config\Config::DB_PASS, [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION]);
    }
    public static function getInstance(): Database {
        if (!self::\$instance) self::\$instance = new Database();
        return self::\$instance;
    }
    public function getConnection(): PDO { return \$this->pdo; }
}

?>