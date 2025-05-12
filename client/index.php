<?php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

// Check for login; if not, show login/register options.
if (!isset($_SESSION['user'])) {
    header("Location: ../client/pages/login.php");
    exit();
}
?>
<?php include 'includes/header.php'; ?>

<main>
    <?php
    // This is the client dashboard – you can include logic to display account details, available loans, auctions, etc.
    include 'pages/dashboard.php';
    ?>
</main>

<?php include 'includes/footer.php'; ?>
