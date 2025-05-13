<?php
require_once __DIR__.'/../bootstrap.php';
use App\Loan;
requireRole('admin');
$pending = Loan::getPending();
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $action = $_POST['action'];
    $loanId = (int)$_POST['loan_id'];
    $adminId = currentUser()->id;
    if ($action==='approve') Loan::approve($loanId, $adminId);
    if ($action==='reject') Loan::reject($loanId, $adminId);
    header('Location: manage_loans.php'); exit;
}
include 'includes/header.php';
?>
<div class="container mt-4">
  <h2>Pending Loan Applications</h2>
  <?php if ($pending): ?>
  <table class="table">
    <thead><tr><th>ID</th><th>Client Email</th><th>Amount</th><th>Term</th><th>Applied On</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($pending as $l): ?>
    <tr>
      <td><?=$l['id']?></td>
      <td><?=htmlspecialchars($l['email'])?></td>
      <td><?=number_format($l['amount'],2)?></td>
      <td><?=$l['term_type']?></td>
      <td><?=$l['created_at']?></td>
      <td>
        <form method="post" style="display:inline">
          <input type="hidden" name="loan_id" value="<?=$l['id']?>">
          <button name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
        </form>
        <form method="post" style="display:inline">
          <input type="hidden" name="loan_id" value="<?=$l['id']?>">
          <button name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
        </form>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
  <?php else: ?>
    <p>No pending applications.</p>
  <?php endif; ?>
</div>
<?php include 'includes/footer.php'; ?>
