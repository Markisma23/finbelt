<?php
require_once __DIR__ . '/../includes/classes/Client.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    
    try {
        if (Client::register($name, $email, $password)) {
            header("Location: login.php");
            exit;
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
include_once __DIR__ . '/../templates/header.php';
?>
<h2>Register</h2>
<?php if(isset($error)) echo "<p style='color:red;'>Error: $error</p>"; ?>
<form method="post" action="">
    <label>Name:</label>
    <input type="text" name="name" required /><br/>
    
    <label>Email:</label>
    <input type="email" name="email" required /><br/>
    
    <label>Password:</label>
    <input type="password" name="password" required /><br/>
    
    <button type="submit">Register</button>
</form>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
