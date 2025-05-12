<?php
// client/includes/header.php
session_start();

// Check for a language change via URL parameter.
if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Use the session language if set; otherwise, default to English.
$lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

require_once('../../classes/Localization.php');
require_once('../../helpers/translate.php');

// Instantiate the localization object.
$localization = new Localization($lang);
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo _t('welcome'); ?> - Finbelt Microfinance</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <header>
        <h1><?php echo _t('welcome'); ?> to Finbelt Microfinance</h1>
        <nav>
            <a href="dashboard.php"><?php echo _t('dashboard'); ?></a> |
            <a href="apply_loan.php"><?php echo _t('apply_loan'); ?></a> |
            <a href="wallet.php"><?php echo _t('wallet'); ?></a> |
            <a href="support_chat.php"><?php echo _t('support_chat'); ?></a> |
            <a href="referral_program.php"><?php echo _t('referral_program'); ?></a> |
            <a href="feedback.php"><?php echo _t('feedback'); ?></a> |
            <a href="logout.php"><?php echo _t('logout'); ?></a>
        </nav>
        <!-- Language Selector -->
        <form method="get" action="" id="langForm">
            <select name="lang" onchange="document.getElementById('langForm').submit();">
                <option value="en" <?php if($lang=='en') echo 'selected'; ?>>English</option>
                <option value="sw" <?php if($lang=='sw') echo 'selected'; ?>>Swahili</option>
                <!-- Add additional languages as needed -->
            </select>
        </form>
    </header>
