<?php
require_once __DIR__ . '/../bootstrap.php';
use App\Loan;
requireRole('client');
$u = currentUser();
$loans = Loan::getByClient($u->id);
include 'includes/header.php';
?>
<div class="container mt-4">
  <h2>My Loans</h2>
  <?php if ($loans): ?>
    <table class="table">
      <thead><tr><th>ID</th><th>Amount</th><th>Term</th><th>Status</th><th>Due Date</th></tr></thead>
      <tbody>
      <?php foreach ($loans as $l): ?>
      <tr>
        <td><?=$l['id']?></td>
        <td><?=number_format($l['amount'],2)?></td>
        <td><?=$l['term_type']?></td>
        <td><?=$l['status']?></td>
        <td><?=$l['due_date']?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>You have no loan applications.</p>
  <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>

?>