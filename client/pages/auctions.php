<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$auctionObj = new Auction();
$biddingMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bid_amount'], $_POST['auction_id'])) {
    $bidAmount = floatval($_POST['bid_amount']);
    $auctionId = intval($_POST['auction_id']);
    $bid = new Bid();
    if ($bid->placeBid($auctionId, $_SESSION['user']['id'], $bidAmount)) {
        $biddingMessage = "Your bid of ZMW {$bidAmount} was successfully placed.";
        // Optional: Re-run analysis to update notifications based on bidding
        $notify = new Notification();
        $notify->analyzeBidHistory($_SESSION['user']['id']);
    } else {
        $biddingMessage = "Bid placement failed. Please try again.";
    }
}

$activeAuctions = $auctionObj->getActiveAuctions();
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Active Auctions</h2>
    <?php if($biddingMessage): ?>
        <p style="color:green;"><?php echo $biddingMessage; ?></p>
    <?php endif; ?>
    <?php if (count($activeAuctions) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Auction ID</th>
                    <th>Collateral</th>
                    <th>Start Price</th>
                    <th>Current Price</th>
                    <th>Place Your Bid</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($activeAuctions as $auction): ?>
                    <tr>
                        <td><?php echo $auction['id']; ?></td>
                        <td><?php echo htmlspecialchars($auction['collateral']); ?></td>
                        <td><?php echo number_format($auction['start_price'],2); ?></td>
                        <td><?php echo number_format($auction['current_price'],2); ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="auction_id" value="<?php echo $auction['id']; ?>">
                                <input type="number" step="0.01" name="bid_amount" placeholder="Your bid amount" required>
                                <button type="submit">Bid</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No active auctions at the moment.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
