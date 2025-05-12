<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Retrieve loans that are approved and open for investment.
// This assumes that approved loans are open for funding until fully invested.
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT l.*, 
    (SELECT IFNULL(SUM(amount),0) FROM investments WHERE loan_id = l.id) as total_invested 
    FROM loans l 
    WHERE l.status = 'approved' 
    ORDER BY l.applied_at DESC");
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Available Investment Opportunities</h2>
    <?php if(count($loans) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Loan ID</th>
                    <th>Amount (ZMW)</th>
                    <th>Collateral</th>
                    <th>Applied On</th>
                    <th>Funding Progress</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach($loans as $loan): 
                $loanAmount = $loan['amount'];
                $invested = $loan['total_invested'];
                $remaining = $loanAmount - $invested;
            ?>
                <tr>
                    <td><?php echo $loan['id']; ?></td>
                    <td>ZMW <?php echo number_format($loanAmount, 2); ?></td>
                    <td><?php echo htmlspecialchars($loan['collateral']); ?></td>
                    <td><?php echo $loan['applied_at']; ?></td>
                    <td>
                        <?php echo "Invested: ZMW " . number_format($invested,2) . " (Remaining: ZMW " . number_format($remaining,2) . ")"; ?>
                    </td>
                    <td>
                        <a href="invest.php?loan_id=<?php echo $loan['id']; ?>">Invest Now</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No investment opportunities available at this time.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
