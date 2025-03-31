<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}
$user = unserialize($_SESSION['user']);
if ($user->role !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}
include_once __DIR__ . '/../templates/header.php';
?>
<h2>Admin Dashboard</h2>
<p>Welcome, <?php echo htmlspecialchars($user->name); ?>!</p>
<p>
    <a href="loans.php">Manage Loans</a> | 
    <a href="auctions.php">Manage Auctions</a> | 
    <a href="settings.php">Settings</a> | 
    <a href="admin_management.php">Admin Management</a>
</p>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
