<?php
require_once 'Database.php';

abstract class User {
    public $id;
    public $name;
    public $email;
    public $password;
    public $role; // 'client' or 'admin'
    
    public function __construct($id, $name, $email, $password, $role) {
        $this->id       = $id;
        $this->name     = $name;
        $this->email    = $email;
        $this->password = $password;
        $this->role     = $role;
    }
    
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }
    
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    // Generic login process: Returns an instance of Client or Admin if successful
    public static function login($email, $password) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
       // Existing code inside User::login()
if ($user && self::verifyPassword($password, $user['password'])) {
    if ($user['role'] === 'admin') {
        require_once 'Admin.php';
        return new Admin($user['id'], $user['name'], $user['email'], $user['password'], $user['permissions']);
    } elseif ($user['role'] === 'investor') {
        require_once 'Investor.php';
        return new Investor($user['id'], $user['name'], $user['email'], $user['password']);
    } else {
        require_once 'Client.php';
        return new Client($user['id'], $user['name'], $user['email'], $user['password']);
    }
}

        return false;
    }
}
