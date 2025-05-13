<?php
/**
 * Finbelt Loan System
 * 
 * Directory Structure and Auto-Creation Script:
 *
 * /index.php
 * /config/config.php
 * /autoload.php
 * /classes/
 *     Core/
 *         Router.php
 *         Controller.php
 *         Model.php
 *         View.php
 *         Database.php
 *         Mailer.php
 *         Backup.php
 *     Entities/
 *         User.php
 *         Admin.php
 *         Loan.php
 *         Auction.php
 *         Bid.php
 *         Collateral.php
 *         Notification.php
 *     Services/
 *         AuthService.php
 *         LoanService.php
 *         AuctionService.php
 *         NotificationService.php
 *         BackupService.php
 *         TranslationService.php
 * /admin/
 *     includes/header.php
 *     includes/footer.php
 *     dashboard.php
 *     users.php
 *     loans.php
 *     auctions.php
 *     backups.php
 *     permissions.php
 * /client/
 *     includes/header.php
 *     includes/footer.php
 *     dashboard.php
 *     apply_loan.php
 *     auctions.php
 *     bid.php
 *     profile.php
 * /lang/
 *     en.php
 *     fr.php
 *     ...
 * /public/
 *     assets/css/
 *     assets/js/
 *     uploads/collateral/
 *
 * To automate directory creation, run create_directories.php once in the root.
 */

// create_directories.php

$directories = [
    'config',
    'classes/Core',
    'classes/Entities',
    'classes/Services',
    'admin/includes',
    'client/includes',
    'lang',
    'public/assets/css',
    'public/assets/js',
    'public/uploads/collateral'
];

foreach ($directories as $dir) {
    $path = __DIR__ . DIRECTORY_SEPARATOR . $dir;
    if (!is_dir($path)) {
        if (mkdir($path, 0755, true)) {
            echo "Created directory: $path\n";
        } else {
            echo "Failed to create: $path\n";
        }
    } else {
        echo "Already exists: $path\n";
    }
}

exit;
?>
