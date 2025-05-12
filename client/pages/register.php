<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Registration - Finbelt Microfinance</title>
    <link rel="stylesheet" href="../../assets/css/client-style.css">
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <?php if($message): ?>
            <p style="color:red;"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" id="confirm_password" name="confirm_password" required>
            
            <button type="submit">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login Here</a></p>
    </div>
</body>
</html>
