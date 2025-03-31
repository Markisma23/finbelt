<?php
require_once __DIR__ . '/../includes/classes/User.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    
    $user = User::login($email, $password);
    if ($user) {
        session_start();
        $_SESSION['user'] = serialize($user);
        // Redirect based on role
        if ($user->role === 'admin') {
            header("Location: ../admin/index.php");
        } else {
            header("Location: ../client/index.php");
        }
        exit;
    } else {
        $error = "Invalid credentials.";
    }
}
include_once __DIR__ . '/../templates/header.php';
?>
<h2>Login</h2>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post" action="">
    <label>Email:</label>
    <input type="email" name="email" required /><br/>
    
    <label>Password:</label>
    <input type="password" name="password" required /><br/>
    
    <button type="submit">Login</button>
</form>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
