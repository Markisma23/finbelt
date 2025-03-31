<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}
$user = unserialize($_SESSION['user']);
if ($user->role !== 'admin' || $user->permissions !== 'all') {
    echo "Access denied. You do not have sufficient permissions to manage admin accounts.";
    exit;
}
require_once __DIR__ . '/../includes/classes/Admin.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name        = trim($_POST['name']);
    $email       = trim($_POST['email']);
    $password    = $_POST['password'];
    $permissions = trim($_POST['permissions']); // 'all' or 'limited'
    
    try {
        if ($user->createAdminAccount($name, $email, $password, $permissions)) {
            $message = "New admin account created successfully.";
        } else {
            $message = "Failed to create admin account.";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
include_once __DIR__ . '/../templates/header.php';
?>
<h2>Admin Management</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
<form method="post" action="">
    <label>Name:</label>
    <input type="text" name="name" required /><br/>
    
    <label>Email:</label>
    <input type="email" name="email" required /><br/>
    
    <label>Password:</label>
    <input type="password" name="password" required /><br/>
    
    <label>Permissions:</label>
    <select name="permissions">
        <option value="limited">Limited (Support Access)</option>
        <option value="all">Full Access</option>
    </select><br/>
    
    <button type="submit">Create Admin Account</button>
</form>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
