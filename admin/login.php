<?php
require_once __DIR__ . '/../bootstrap.php';
use App\User, App\Notification;
if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
    \$user = User::login(\$_POST['email'], \$_POST['password']);
    if (\$user && in_array(\$user->role, ['admin','super_admin'])) {
        \$_SESSION['user_id'] = \$user->id;
        Notification::sendEmail(\$user->email, 'Admin Login', 'You have logged in as admin.');
        header('Location: index.php'); exit;
    }
    \$error = 'Invalid admin credentials';
}
include 'includes/header.php';
?>
<form method="post">
  <input name="email" type="email" required>
  <input name="password" type="password" required>
  <button type="submit">Admin Login</button>
  <?php if (isset(\$error)) echo "<p>\$error</p>"; ?>
</form>
<?php include 'includes/footer.php'; ?>