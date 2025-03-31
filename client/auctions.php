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
require_once __DIR__ . '/../includes/classes/Auction.php';
include_once __DIR__ . '/../templates/header.php';

// Fetch active auctions
$auctions = Auction::getActiveAuctions();
?>
<h2>Active Auctions</h2>
<?php if ($auctions): ?>
    <ul>
    <?php foreach ($auctions as $auction): ?>
        <li>
            <p>Auction ID: <?php echo $auction['id']; ?></p>
            <p>Collateral: <?php echo htmlspecialchars($auction['collateral']); ?></p>
            <p>Category: <?php echo htmlspecialchars($auction['category']); ?></p>
            <form method="post" action="place_bid.php">
                <input type="hidden" name="auction_id" value="<?php echo $auction['id']; ?>" />
                <label>Your Bid:</label>
                <input type="number" name="bid_amount" step="0.01" required />
                <button type="submit">Place Bid</button>
            </form>
        </li>
    <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No active auctions at the moment.</p>
<?php endif; ?>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
