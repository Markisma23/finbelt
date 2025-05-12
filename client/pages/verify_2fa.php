<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['pending_user'])) {
    header("Location: login.php");
    exit();
}

$twoFA = new TwoFactorAuth();
$user = $_SESSION['pending_user'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verification_code'])) {
    $code = trim($_POST['verification_code']);
    if ($twoFA->verifyCode($user['totp_secret'], $code)) {
        // Successful 2FA.
        $_SESSION['user'] = $user;
        unset($_SESSION['pending_user']);
        header("Location: ../index.php");
        exit();
    } else {
        $message = "Invalid 2FA code. Please try again.";
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Two-Factor Authentication Verification</h2>
    <?php if($message): ?>
        <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="verification_code">Enter the 6-digit code from your authenticator app:</label>
        <input type="text" name="verification_code" id="verification_code" maxlength="6" required>
        <button type="submit">Verify</button>
    </form>
</main>
<?php include '../includes/footer.php'; ?>
