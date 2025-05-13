<?php
require_once __DIR__ . '/../bootstrap.php';
requireRole('admin');
include 'includes/header.php';
?>
<h1>Admin Dashboard</h1>
<!-- stats, links to manage_loans.php, auctions.php, manage_admins.php, backup.php -->
<?php include 'includes/footer.php'; ?>