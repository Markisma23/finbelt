<?php
session_start();
spl_autoload_register(function($class) {
    $file = __DIR__ . '/classes/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) include $file;
});
use App\User;

function currentUser() {
    if (!empty($_SESSION['user_id'])) {
        return User::findById($_SESSION['user_id']);
    }
    return null;
}
function requireLogin() {
    if (!currentUser()) header('Location: login.php') && exit;
}
function requireRole($role) {
    $u = currentUser();
    if (!$u || $u->role !== $role) header('Location: login.php') && exit;
}

function requireLocalization($lan) {
if (!$l || $l->role !== $lan) header('Localization: lan.php') && exit; 
}
?>