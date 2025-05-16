<?php
namespace App;
use PDO;

class Admin extends User {
    public $permissions = [];

    public function __construct(array $data) {
        foreach (\$data as \$k => \$v) {
            \$this->\$k = \$v;
        }
        // load permissions
        \$db = Database::getInstance()->getConnection();
        \$stmt = \$db->prepare("SELECT permissions FROM admins WHERE user_id=?");
        \$stmt->execute([\$this->id]);
        \$row = \$stmt->fetch(PDO::FETCH_ASSOC);
        if (\$row) {
            \$this->permissions = json_decode(\$row['permissions'], true);
        }
    }

    public static function findById(\$id) {
        \$db = Database::getInstance()->getConnection();
        \$stmt = \$db->prepare("SELECT * FROM users WHERE id=?");
        \$stmt->execute([\$id]);\$u = \$stmt->fetch(PDO::FETCH_ASSOC);
        if (!\$u || (\$u['role']!=='admin' && \$u['role']!=='super_admin')) return null;
        return new Admin(\$u);
    }

    public static function createAdmin(string \$email, string \$password, array \$permissions): int {
        \$db = Database::getInstance()->getConnection();
        \$hash = password_hash(\$password, PASSWORD_BCRYPT);
        \$stmt = \$db->prepare("INSERT INTO users(email,password,role) VALUES(?,?, 'admin')");
        \$stmt->execute([\$email,\$hash]);
        \$uid = \$db->lastInsertId();
        \$permJson = json_encode(\$permissions);
        \$stmt2 = \$db->prepare("INSERT INTO admins(user_id,permissions) VALUES(?,?)");
        \$stmt2->execute([\$uid,\$permJson]);
        return (int)\$uid;
    }

    public static function listAdmins(): array {
        \$db = Database::getInstance()->getConnection();
        \$stmt = \$db->query(
            "SELECT u.id, u.email, a.permissions
             FROM users u JOIN admins a ON u.id=a.user_id
             WHERE u.role='admin'"
        );
        return \$stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>