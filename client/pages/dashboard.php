<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Retrieve wallet balance and recent loan applications (example as before).
$walletObj = new Wallet();
$wallet = $walletObj->getWallet($_SESSION['user']['id']);
$loanObj = new Loan();
$loans = $loanObj->getUserLoans($_SESSION['user']['id']);
$recentLoans = array_slice($loans, 0, 5);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2><?php echo _t('welcome'); ?>, <?php echo htmlspecialchars($_SESSION['user']['username']); ?>!</h2>
    <section>
        <h3><?php echo _t('wallet'); ?></h3>
        <p><strong><?php echo _t('wallet'); ?> Balance:</strong> ZMW <?php echo number_format($wallet ? $wallet['balance'] : 0, 2); ?></p>
    </section>
    
    <section>
        <h3><?php echo _t('apply_loan'); ?> - <?php echo _t('Recent Loan Applications'); ?></h3>
        <?php if (count($recentLoans) > 0): ?>
            <table border="1" cellpadding="5">
                <thead>
                    <tr>
                        <th>Loan ID</th>
                        <th><?php echo _t('loan_amount'); ?></th>
                        <th><?php echo _t('collateral'); ?></th>
                        <th>Status</th>
                        <th>Applied On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentLoans as $loan): ?>
                        <tr>
                            <td><?php echo $loan['id']; ?></td>
                            <td>ZMW <?php echo number_format($loan['amount'], 2); ?></td>
                            <td><?php echo htmlspecialchars($loan['collateral']); ?></td>
                            <td><?php echo ucfirst($loan['status']); ?></td>
                            <td><?php echo $loan['applied_at']; ?></td>
                            <td><a href="approval_history.php?loan_id=<?php echo $loan['id']; ?>"><?php echo _t('View History'); ?></a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><?php echo _t('No loan applications yet.'); ?></p>
        <?php endif; ?>
    </section>
    
    <section>
        <h3>Quick Actions</h3>
        <ul>
            <li><a href="apply_loan.php"><?php echo _t('apply_loan'); ?></a></li>
            <li><a href="wallet.php"><?php echo _t('wallet'); ?></a></li>
            <li><a href="support_chat.php"><?php echo _t('support_chat'); ?></a></li>
        </ul>
    </section>
</main>
<?php include '../includes/footer.php'; ?>
