<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}
$user = unserialize($_SESSION['user']);
if ($user->role !== 'client') {
    header("Location: ../public/login.php");
    exit;
}
include_once __DIR__ . '/../templates/header.php';
?>
<h2>My Profile</h2>
<p>Name: <?php echo htmlspecialchars($user->name); ?></p>
<p>Email: <?php echo htmlspecialchars($user->email); ?></p>
<!-- Additional profile information and bidding history can be added here -->
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
