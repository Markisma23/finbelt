<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Check permission: Only super_admin can create other admins.
if ($_SESSION['user']['role'] !== 'super_admin') {
    echo "Access denied: Insufficient permissions.";
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    
    $admin = new Admin();
    if ($admin->createAdmin($username, $email, $password)) {
        $message = "Admin account created successfully.";
    } else {
        $message = "Failed to create admin account.";
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Create New Admin Account</h2>
    <?php if($message): ?>
        <p><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="username">Admin Username:</label>
        <input type="text" name="username" id="username" required>
        
        <label for="email">Admin Email:</label>
        <input type="email" name="email" id="email" required>
        
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        
        <button type="submit">Create Admin</button>
    </form>
</main>
<?php include '../includes/footer.php'; ?>
