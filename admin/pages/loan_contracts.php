<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Only allow admin access.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'super_admin')) {
    header("Location: login.php");
    exit();
}

$loanId = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : 0;

// If a specific loan is requested, show its contract. Otherwise, list all contracts.
$db = Database::getInstance()->getConnection();
if ($loanId) {
    $stmt = $db->prepare("SELECT * FROM loan_contracts WHERE loan_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$loanId]);
    $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $db->query("SELECT lc.*, l.amount, u.username FROM loan_contracts lc JOIN loans l ON lc.loan_id = l.id JOIN users u ON l.user_id = u.id ORDER BY lc.created_at DESC");
    $contracts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Loan Contracts</h2>
    <?php if (count($contracts) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Contract ID</th>
                    <th>Loan ID</th>
                    <th>Amount</th>
                    <th>Borrower</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Signed At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($contracts as $contract): ?>
                    <tr>
                        <td><?php echo $contract['id']; ?></td>
                        <td><?php echo $contract['loan_id']; ?></td>
                        <td>ZMW <?php echo number_format($contract['amount'],2); ?></td>
                        <td><?php echo htmlspecialchars($contract['username']); ?></td>
                        <td><?php echo ucfirst($contract['status']); ?></td>
                        <td><?php echo $contract['created_at']; ?></td>
                        <td><?php echo $contract['signed_at'] ? $contract['signed_at'] : 'Not Signed'; ?></td>
                        <td>
                            <a href="../../<?php echo htmlspecialchars($contract['contract_path']); ?>" target="_blank">View Contract</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No contracts found.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
