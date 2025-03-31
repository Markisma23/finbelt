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
<h2>Admin Settings</h2>
<p>Configure system settings here.</p>
<!-- Add settings functionality as needed -->
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
