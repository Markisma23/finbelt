<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$investmentObj = new Investment();
$investments = $investmentObj->getInvestmentsByInvestor($_SESSION['user']['id']);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Your Investments</h2>
    <?php if (count($investments) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Investment ID</th>
                    <th>Loan ID</th>
                    <th>Invested Amount (ZMW)</th>
                    <th>Loan Amount (ZMW)</th>
                    <th>Collateral</th>
                    <th>Invested At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($investments as $inv): ?>
                    <tr>
                        <td><?php echo $inv['id']; ?></td>
                        <td><?php echo $inv['loan_id']; ?></td>
                        <td>ZMW <?php echo number_format($inv['amount'], 2); ?></td>
                        <td>ZMW <?php echo number_format($inv['loan_amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($inv['collateral']); ?></td>
                        <td><?php echo $inv['invested_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have not invested in any loans yet.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
