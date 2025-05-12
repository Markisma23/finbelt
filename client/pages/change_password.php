<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$profile = new Profile();
$userId = $_SESSION['user']['id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_password'], $_POST['confirm_password'])) {
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];
    
    if ($newPassword !== $confirmPassword) {
        $message = "Passwords do not match.";
    } else {
        if ($profile->updatePassword($userId, $newPassword)) {
            $message = "Password updated successfully.";
        } else {
            $message = "Failed to update password.";
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Change Password</h2>
    <?php if($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="new_password">New Password:</label>
        <input type="password" id="new_password" name="new_password" required><br><br>
        
        <label for="confirm_password">Confirm New Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>
        
        <button type="submit">Update Password</button>
    </form>
</main>
<?php include '../includes/footer.php'; ?>
