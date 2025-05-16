//========== index.php ==========
<?php
require_once 'bootstrap.php';
use App\Translation;
\$lang = $_GET['lang'] ?? ($_SESSION['lang'] ?? 'en');
if (\$_GET['lang'] && in_array($lang, array_keys(\Config\Config::\$LANGUAGES))) {
    \$_SESSION['lang'] = \$lang;
    Translation::load(\$lang);
}
?>
<!doctype html>
<html lang="<?= htmlspecialchars(\$lang) ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= T('welcome') ?> - Finbelt Microfinance</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <?php include 'client/includes/header.php'; ?>
  <main class="container my-5">
    <h2><?= T('register') ?></h2>
    <!-- Landing content... -->
  </main>
  <?php include 'client/includes/footer.php'; ?>
</body>
</html>