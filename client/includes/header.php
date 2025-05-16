<?php
function langSwitcher() {
    $opts = '';
    foreach (\Config\Config::$LANGUAGES as $code => $label) {
        $sel = ($_SESSION['lang']===$code)?'selected':'';
        $opts .= "<option value='?lang=$code' $sel>$label</option>";
    }
    return $opts;
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">Finbelt</a>
    <div>
      <select onchange="location = this.value;" class="form-select form-select-sm">
        <?= langSwitcher() ?>
      </select>
    </div>
    <div class="ms-auto">
      <?php if (currentUser()): ?>
        <a href="profile.php" class="btn btn-outline-primary me-2"><?= T('profile') ?></a>
        <a href="logout.php" class="btn btn-outline-danger"><?= T('logout') ?></a>
      <?php else: ?>
        <a href="login.php" class="btn btn-outline-success me-2"><?= T('client_login') ?></a>
        <a href="register.php" class="btn btn-primary"><?= T('register') ?></a>
      <?php endif; ?>
    </div>
  </div>
</nav>