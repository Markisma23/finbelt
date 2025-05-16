<?php
session_start();
if (!isset($_SESSION['lang'])) { $_SESSION['lang'] = 'en'; }
spl_autoload_register(function($class) {
    $file = __DIR__ . '/classes/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) include $file;
});
use App\User, App\Translation;
Translation::load($_SESSION['lang']);
define('T', function($key) {
    return Translation::get($key);
});

function currentUser() {
    return !empty($_SESSION['user_id']) ? User::findById($_SESSION['user_id']) : null;
}
function requireLogin() { if (!currentUser()) { header('Location: login.php'); exit; } }
function requireRole($role) { $u = currentUser(); if (!$u||$u->role!==$role) { header('Location: login.php'); exit; } }
function requirePermission($perm) { $u = currentUser(); if (!$u||($u->role!=='super_admin'&&!in_array($perm, $u->permissions ?? []))) { header('HTTP/1.1 403 Forbidden'); echo T('access_denied'); exit; } }
?>