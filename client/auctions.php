<?php
require_once __DIR__ . '/../bootstrap.php';
use App\Auction;
requireRole('client');
$auctions = Auction::listOpen();
include 'includes/header.php';
?>
<div class="container mt-4">
  <h2>Open Auctions</h2>
  <div class="row">
    <?php foreach ($auctions as $a): ?>
    <div class="col-md-4 mb-3">
      <div class="card">
        <img src="../<?=htmlspecialchars($a['image_path'])?>" class="card-img-top">
        <div class="card-body">
          <h5 class="card-title"><?=htmlspecialchars($a['item_name'])?></h5>
          <p>Current Bid: ZMW <?=number_format($a['current_bid'] ?? 0,2)?></p>
          <p>Ends: <?=$a['end_date']?></p>
          <form method="post" action="auctions.php">
            <input type="hidden" name="auction_id" value="<?=$a['id']?>">
            <div class="input-group mb-2">
              <input type="number" name="amount" step="0.01" required class="form-control">
              <button type="submit" class="btn btn-primary">Bid</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>
<?php include 'includes/footer.php'; ?>
// Handle bid submission
<?php
if (\$_SERVER['REQUEST_METHOD']==='POST' && isset(\$_POST['auction_id'])) {
    \$auc = new Auction(\$_POST['auction_id']);
    try {
        \$auc->placeBid(currentUser()->id, (float)\$_POST['amount']);
        header('Location: auctions.php?bid=success'); exit;
    } catch (\Exception \$e) {
        \$error = \$e->getMessage();
    }
}
?>