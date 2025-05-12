<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$db = Database::getInstance()->getConnection();
// Retrieve defaulted loans for the logged-in client.
$stmt = $db->prepare("SELECT * FROM loans WHERE user_id = ? AND status = 'defaulted'");
$stmt->execute([$_SESSION['user']['id']]);
$defaultedLoans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Your Defaulted Loans</h2>
    <?php if (count($defaultedLoans) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Loan Amount (ZMW)</th>
                    <th>Collateral</th>
                    <th>Applied On</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($defaultedLoans as $loan): ?>
                    <tr>
                        <td><?php echo $loan['id']; ?></td>
                        <td><?php echo number_format($loan['amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($loan['collateral']); ?></td>
                        <td><?php echo $loan['applied_at']; ?></td>
                        <td><?php echo ucfirst($loan['status']); ?></td>
                        <td>
                            <!-- Provide a link to view or participate in collateral auction if available -->
                            <a href="auction_detail.php?id=<?php echo $loan['id']; ?>">View Auction</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no defaulted loans.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
