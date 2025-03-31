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

require_once __DIR__ . '/../includes/classes/Repurchase.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $auctionId   = intval($_POST['auction_id']);
    $offeredPrice = floatval($_POST['offered_price']);
    try {
        if (Repurchase::submitRequest($auctionId, $user->id, $offeredPrice)) {
            $message = "Repurchase request submitted successfully.";
        } else {
            $message = "Failed to submit repurchase request.";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

include_once __DIR__ . '/../templates/header.php';
?>
<h2>Repurchase Your Collateral</h2>
<?php if ($message): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
<form method="post" action="">
    <!-- In a real system, you might pre-fill auction details -->
    <label>Auction ID:</label>
    <input type="number" name="auction_id" required /><br/>
    
    <label>Offer Price:</label>
    <input type="number" name="offered_price" step="0.01" required /><br/>
    
    <button type="submit">Submit Repurchase Request</button>
</form>
<hr>
<h3>Your Repurchase Requests</h3>
<?php
$requests = Repurchase::getRequestsForClient($user->id);
if ($requests):
?>
<table border="1" cellpadding="5">
    <tr>
        <th>Request ID</th>
        <th>Auction ID</th>
        <th>Offer Price</th>
        <th>Status</th>
        <th>Submitted At</th>
    </tr>
    <?php foreach ($requests as $req): ?>
    <tr>
        <td><?php echo $req['id']; ?></td>
        <td><?php echo $req['auction_id']; ?></td>
        <td><?php echo $req['offered_price']; ?></td>
        <td><?php echo $req['status']; ?></td>
        <td><?php echo $req['created_at']; ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p>You have not submitted any repurchase requests.</p>
<?php endif; ?>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
