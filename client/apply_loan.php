<?php
require_once __DIR__ . '/../bootstrap.php';
use App\Loan, App\Notification;
requireRole('client');
$u = currentUser();
if (\$_SERVER['REQUEST_METHOD']==='POST') {
    // handle collateral upload
    \$targetDir = __DIR__ . '/../uploads/collateral/';
    \$fileName = uniqid('col_') . '_' . basename(\$_FILES['collateral']['name']);
    \$filePath = \$targetDir . \$fileName;
    move_uploaded_file(\$_FILES['collateral']['tmp_name'], \$filePath);
    // create loan
    \$loanId = (new Loan())->create(\$u->id, \$_POST['amount'], \$_POST['term'], 'uploads/collateral/' . \$fileName);
    // redirect to my_loans
    header('Location: my_loans.php?applied=1'); exit;
}
include 'includes/header.php';
?>
<div class="container mt-4">
  <h2>Apply for a Loan</h2>
  <form method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label>Amount (ZMW)</label>
      <input name="amount" type="number" min="1000" required class="form-control">
    </div>
    <div class="mb-3">
      <label>Term</label>
      <select name="term" class="form-select">
        <option value="monthly">35% Monthly</option>
        <option value="weekly">10% Weekly</option>
      </select>
    </div>
    <div class="mb-3">
      <label>Collateral Image</label>
      <input name="collateral" type="file" accept="image/*" required class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Submit Application</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>