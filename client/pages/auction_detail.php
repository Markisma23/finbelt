<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$auctionId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$auctionId) {
    echo "Invalid auction specified.";
    exit();
}

$auctionObj = new Auction();
// In production, you should write a function to get a single auction detail.
$stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM auctions WHERE id = ?");
$stmt->execute([$auctionId]);
$auction = $stmt->fetch(PDO::FETCH_ASSOC);

$bidObj = new Bid();
$bids = $bidObj->getUserBids($_SESSION['user']['id']); // Or create a method to get all bids for the auction.

?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Auction #<?php echo htmlspecialchars($auction['id']); ?></h2>
    <p>Collateral: <?php echo htmlspecialchars($auction['collateral']); ?></p>
    <p>Start Price: ZMW <?php echo number_format($auction['start_price'],2); ?></p>
    <p>Current Price: ZMW <?php echo number_format($auction['current_price'],2); ?></p>
    <p>Status: <?php echo ucfirst($auction['status']); ?></p>
    <p>Auction End: <?php echo htmlspecialchars($auction['auction_end']); ?></p>
    
    <h3>Place a Bid</h3>
    <form method="post" action="../pages/auctions.php">
        <input type="hidden" name="auction_id" value="<?php echo htmlspecialchars($auction['id']); ?>">
        <input type="number" step="0.01" name="bid_amount" placeholder="Your bid amount" required>
        <button type="submit">Bid</button>
    </form>
    
    <h3>Your Bidding History</h3>
    <!-- You might list all bids placed on this auction -->
</main>
<?php include '../includes/footer.php'; ?>
