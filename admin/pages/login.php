<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Redirect already logged in admin.
if (isset($_SESSION['user']) && ($_SESSION['user']['role'] == 'admin' || $_SESSION['user']['role'] == 'super_admin')) {
    header("Location: ../index.php");
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['username_email']);
    $password = $_POST['password'];
    
    $user = new User();
    if ($user->login($usernameOrEmail, $password)) {
        // Check role for admin
        if ($_SESSION['user']['role'] === 'admin' || $_SESSION['user']['role'] === 'super_admin') {
            header("Location: ../index.php");
            exit();
        } else {
            $message = "Access denied. Not an admin account.";
            unset($_SESSION['user']);
        }
    } else {
        $message = "Invalid credentials.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Finbelt Microfinance</title>
    <link rel="stylesheet" href="../../assets/css/admin-style.css">
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>
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
    </div>
</body>
</html>
