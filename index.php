<?php
// index.php (root)
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/classes/Translation.php';

use App\Translation;

// Determine language (default to English)
$lang = $_GET['lang'] ?? 'en';
Translation::load($lang);
?>
<!doctype html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= Translation::t('Finbelt Microfinance') ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <header class="bg-dark text-white py-3 mb-4">
    <div class="container d-flex justify-content-between align-items-center">
      <h1 class="h3"><?= Translation::t('Finbelt Microfinance') ?></h1>
      <nav>
        <a href="client/index.php" class="btn btn-outline-light me-2"><?= Translation::t('Client Login') ?></a>
        <a href="admin/index.php" class="btn btn-outline-light"><?= Translation::t('Admin Login') ?></a>
      </nav>
    </div>
  </header>

  <main class="container">
    <div class="row mb-5">
      <div class="col-md-6">
        <h2><?= Translation::t('Our Services') ?></h2>
        <ul>
          <li><?= Translation::t('Collateral-backed micro-loans starting at ZMW 1,000') ?></li>
          <li><?= Translation::t('35% monthly or 10% weekly interest options') ?></li>
          <li><?= Translation::t('Transparent auction-based recovery for defaulted loans') ?></li>
          <li><?= Translation::t('Personalized dashboards for clients & admins') ?></li>
        </ul>
      </div>
      <div class="col-md-6 text-center">
        <img src="assets/loan_banner.jpg" alt="<?= Translation::t('Collateral Loans') ?>" class="img-fluid rounded">
      </div>
    </div>

    <div class="row align-items-center">
      <div class="col-md-4 text-center">
        <i class="bi bi-person-plus display-4"></i>
        <h3><?= Translation::t('Register') ?></h3>
        <p><?= Translation::t('Become a client in just a few clicks.') ?></p>
        <a href="client/register.php" class="btn btn-primary"><?= Translation::t('Sign Up') ?></a>
      </div>
      <div class="col-md-4 text-center">
        <i class="bi bi-clipboard2-check display-4"></i>
        <h3><?= Translation::t('Apply for Loan') ?></h3>
        <p><?= Translation::t('Easy collateral evaluation & instant decision.') ?></p>
        <a href="client/apply_loan.php" class="btn btn-success"><?= Translation::t('Apply Now') ?></a>
      </div>
      <div class="col-md-4 text-center">
        <i class="bi bi-gavel display-4"></i>
        <h3><?= Translation::t('Auctions') ?></h3>
        <p><?= Translation::t('Bid on collateral from defaulted loans.') ?></p>
        <a href="client/auctions.php" class="btn btn-warning"><?= Translation::t('View Auctions') ?></a>
      </div>
    </div>
  </main>

  <footer class="bg-light text-center py-4 mt-5">
    <small>&copy; <?= date('Y') ?> Finbelt Microfinance. <?= Translation::t('All rights reserved.') ?></small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.4.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
