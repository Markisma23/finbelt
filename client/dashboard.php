<?php
require_once __DIR__.'/../bootstrap.php';
requireRole('client');
$u = currentUser();
$loans = Loan::getByClient($u->id);
$recs = Auction::getRecommendations($u->id);
include 'includes/header.php';
?>
<div class="container mt-4">
  <h2>Welcome, <?=htmlspecialchars($u->name)?></h2>
  <h3>Your Loans</h3>
  <?php if($loans): ?>
  <table class="table">
    <thead><tr><th>Amount</th><th>Due Date</th><th>Status</th></tr></thead>
    <tbody>
      <?php foreach($loans as $l): ?>
      <tr>
        <td><?=number_format($l['amount'],2)?></td>
        <td><?=$l['due_date']?></td>
        <td><?=$l['status']?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p>No loans yet. <a href="apply_loan.php">Apply now</a>.</p>
  <?php endif; ?>

  <h3>Recommended Auctions</h3>
  <?php if($recs): ?>
  <div class="row">
    <?php foreach($recs as $a): ?>
    <div class="col-md-4 mb-3">
      <div class="card">
        <img src="../<?=$a['image_path']?>" class="card-img-top">
        <div class="card-body">
          <h5 class="card-title"><?=htmlspecialchars($a['item_name'])?></h5>
          <p class="card-text">Current Bid: <?=number_format($a['current_bid'],2)?></p>
          <a href="auctions.php" class="btn btn-primary">View Auctions</a>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
    <p>No recommendations yet. <a href="auctions.php">Browse auctions</a>.</p>
  <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>

?>