<?php
require_once __DIR__.'/../config/config.php';require_once __DIR__.'/../classes/Database.php';require_once __DIR__.'/../classes/User.php';require_once __DIR__.'/../classes/Client.php';require_once __DIR__.'/../classes/Notification.php';
use App\User;use App\Notification;
if (\$_SERVER['REQUEST_METHOD']==='POST') {
    \$id = User::register(\$_POST['email'], \$_POST['password'], 'client', [
      'name'=>\$_POST['name'],'nrc'=>\$_POST['nrc'],'country'=>\$_POST['country']
    ]);
    Notification::sendEmail(\$_POST['email'],'Welcome','Your account has been created.');
    header('Location: login.php?registered=1');exit;
}
include 'includes/header.php';
?>
<form method="post" enctype="multipart/form-data">
  <input name="name" required placeholder="Full Name">
  <input name="email" type="email" required>
  <input name="password" type="password" required>
  <input name="nrc" required placeholder="NRC Number">
  <input name="country" required placeholder="Country">
  <button type="submit">Register</button>
</form>
<?php include 'includes/footer.php'; ?>