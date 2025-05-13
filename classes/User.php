<?php
namespace App;
use PDO;
class User {
    public $id, $email, $role;
    public static function findById($id) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$id]); $u = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$u) return null;
        if ($u['role']==='client') {
            $stmt2 = $db->prepare("SELECT name,nrc,country FROM clients WHERE user_id=?");
            $stmt2->execute([$id]); $c = $stmt2->fetch(PDO::FETCH_ASSOC);
            return new Client($u + $c);
        }
        return new Admin($u);
    }
    public static function login($email, $pass) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]); $u = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($u && password_verify($pass, $u['password'])) {
            return self::findById($u['id']);
        }
        return null;
    }
    public static function register($email,$pass,$role,$extras=[]) {
        $db = Database::getInstance()->getConnection();
        $hash = password_hash($pass,PASSWORD_BCRYPT);
        $stmt = $db->prepare("INSERT INTO users(email,password,role) VALUES(?,?,?)");
        $stmt->execute([$email,$hash,$role]);
        $uid = $db->lastInsertId();
        if ($role==='client') {
            $st = $db->prepare("INSERT INTO clients(user_id,name,nrc,country) VALUES(?,?,?,?)");
            $st->execute([$uid,$extras['name'],$extras['nrc'],$extras['country']]);
        }
        return (int)$uid;
    }
}

?>