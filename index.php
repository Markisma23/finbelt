<?php
require_once 'config/bootstrap.php';

session_start();

use App\Translation;

// Determine language (GET overrides session, defaults to 'en')
$lang = $_GET['lang'] 
    ?? ($_SESSION['lang'] ?? 'en');

// If there's a new ?lang= and it’s valid, store it
if (isset($_GET['lang'])
    && in_array($_GET['lang'], array_keys(\Config\Config::$LANGUAGES), true)
) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Load the appropriate translations every time
Translation::load($lang);
?>
<!doctype html>
<html lang="<?= htmlspecialchars($lang, ENT_QUOTES, 'UTF-8') ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= T('welcome') ?> - Finbelt Microfinance</title>
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >
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
