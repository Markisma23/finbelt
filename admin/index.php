<?php
require_once '../includes/config.php';
require_once '../includes/autoload.php';

// Check for login and admin role
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: ../admin/pages/login.php");
    exit();
}
?>
<?php include 'includes/header.php'; ?>

<main>
    <?php
    // Include admin dashboard page or redirect based on request
    include 'pages/dashboard.php';
    ?>
</main>

<?php include 'includes/footer.php'; ?>
