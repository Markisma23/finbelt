<?php
require_once __DIR__ . '/../bootstrap.php';
use App\Database, App\Contract;
requireRole('client');

\$db = Database::getInstance()->getConnection();
\$stmt = \$db->prepare(\"SELECT * FROM contracts c
    JOIN loans l ON c.loan_id = l.id
    WHERE l.client_id = ? AND c.approved_at IS NULL
    ORDER BY c.created_at DESC LIMIT 1\");
\$stmt->execute([currentUser()->id]);
\$contract = \$stmt->fetch(PDO::FETCH_ASSOC);

if (!\$contract) {
    header('Location: index.php'); exit;
}

if (\$_SERVER['REQUEST_METHOD'] === 'POST') {
    Contract::approve(\$contract['id']);
    header('Location: my_loans.php?contract_approved=1');
    exit;
}

include 'includes/header.php';
?>
<div class="container mt-4">
  <h2>Please Review & Sign Contract</h2>
  <embed src="../contracts/<?= htmlspecialchars(\$contract['path']) ?>"
         type="application/pdf" width="100%" height="600px" />
  <form method="post" class="mt-3">
    <button type="submit" class="btn btn-success">I Agree & Sign</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
