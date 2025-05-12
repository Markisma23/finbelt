<?php
require_once 'includes/config.php';
require_once 'includes/autoload.php';

// Check if the user is already logged in, then redirect to dashboard.
if (isset($_SESSION['user'])) {
    header("Location: client/index.php");
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['username_email']);
    $password = $_POST['password'];
    
    $user = new User();
    if ($user->login($usernameOrEmail, $password)) {
        header("Location: client/index.php");
        exit();
    } else {
        $message = "Invalid username/email or password.";
    }
}
?>
