<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Check if the user is already logged in, then redirect to dashboard.
if (isset($_SESSION['user'])) {
    header("Location: ../index.php");
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['username_email']);
    $password = $_POST['password'];
    
    $user = new User();
    if ($user->login($usernameOrEmail, $password)) {
        header("Location: ../index.php");
        exit();
    } else {
        $message = "Invalid username/email or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Login - Finbelt Microfinance</title>
    <link rel="stylesheet" href="../../assets/css/client-style.css">
</head>
<body>
    <div class="login-container">
        <h2>Client Login</h2>
        <?php if($message): ?>
            <p style="color:red;"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username_email">Username or Email:</label>
            <input type="text" id="username_email" name="username_email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register Here</a></p>
    </div>
</body>
</html>
