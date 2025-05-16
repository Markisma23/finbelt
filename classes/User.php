<?php
namespace App;
use PDO;

abstract class User {
    public $id, $email, $role;
    abstract public static function findById($id);
    public static function login($email, $pass) {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]); $u = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($u && password_verify($pass, $u['password'])) {
            if ($u['role']==='client') return Client::findById($u['id']);
            return Admin::findById($u['id']);
        }
        return null;
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