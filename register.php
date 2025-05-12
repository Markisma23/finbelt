<?php
require_once 'includes/config.php';
require_once 'includes/autoload.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];
    
    if ($password !== $confirm) {
        $message = "Passwords do not match.";
    } else {
        $user = new User();
        $result = $user->register($username, $email, $password, 'client');
        if ($result) {
            header("Location: login.php");
            exit();
        } else {
            $message = "Registration failed. Please try again.";
        }
    }
}
?>