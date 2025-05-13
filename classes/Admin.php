<?php
namespace App;
class Admin extends User {
    private \$permissions;
    public function __construct(\$data) { foreach(\$data as \$k=>\$v) \$this->\$k=\$v; }
    public function dashboard(){ include __DIR__.'/../admin/index.php'; }
    public static function createAdmin(\$email,\$pass,\$perms){ /*...*/ }
}
?>