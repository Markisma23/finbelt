<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT ld.*, l.amount as loan_amount, u.username 
                    FROM loan_disbursements ld
                    JOIN loans l ON ld.loan_id = l.id
                    JOIN users u ON l.user_id = u.id
                    ORDER BY ld.created_at DESC");
$disbursements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Loan Disbursement Records</h2>
    <?php if (count($disbursements) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Disbursement ID</th>
                    <th>Loan ID</th>
                    <th>Borrower</th>
                    <th>Loan Amount</th>
                    <th>Disbursement Amount</th>
                    <th>Status</th>
                    <th>Scheduled At</th>
                    <th>Disbursed At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($disbursements as $d): ?>
                    <tr>
                        <td><?php echo $d['id']; ?></td>
                        <td><?php echo $d['loan_id']; ?></td>
                        <td><?php echo htmlspecialchars($d['username']); ?></td>
                        <td>ZMW <?php echo number_format($d['loan_amount'], 2); ?></td>
                        <td>ZMW <?php echo number_format($d['disbursement_amount'], 2); ?></td>
                        <td><?php echo ucfirst($d['status']); ?></td>
                        <td><?php echo $d['scheduled_at']; ?></td>
                        <td><?php echo $d['disbursed_at'] ? $d['disbursed_at'] : 'N/A'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No disbursement records available.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
