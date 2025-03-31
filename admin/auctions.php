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
require_once __DIR__ . '/../includes/classes/Auction.php';
include_once __DIR__ . '/../templates/header.php';

// Fetch active auctions
$auctions = Auction::getActiveAuctions();
?>
<h2>Manage Auctions</h2>
<?php if ($auctions): ?>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Loan ID</th>
            <th>Collateral</th>
            <th>Category</th>
            <th>Status</th>
        </tr>
        <?php foreach ($auctions as $auction): ?>
        <tr>
            <td><?php echo $auction['id']; ?></td>
            <td><?php echo $auction['loan_id']; ?></td>
            <td><?php echo htmlspecialchars($auction['collateral']); ?></td>
            <td><?php echo htmlspecialchars($auction['category']); ?></td>
            <td><?php echo $auction['status']; ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No active auctions found.</p>
<?php endif; ?>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
