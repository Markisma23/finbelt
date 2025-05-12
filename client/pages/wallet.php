<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$walletObj = new Wallet();
$userId = $_SESSION['user']['id'];
$wallet = $walletObj->getWallet($userId);
$transactions = $walletObj->getTransactions($userId);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Your Digital Wallet</h2>
    <p><strong>Current Balance:</strong> ZMW <?php echo number_format($wallet ? $wallet['balance'] : 0, 2); ?></p>
    
    <h3>Wallet Transactions</h3>
    <?php if (count($transactions) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Transaction ID</th>
                    <th>Type</th>
                    <th>Amount (ZMW)</th>
                    <th>Description</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $tx): ?>
                    <tr>
                        <td><?php echo $tx['id']; ?></td>
                        <td><?php echo ucfirst($tx['transaction_type']); ?></td>
                        <td><?php echo number_format($tx['amount'], 2); ?></td>
                        <td><?php echo htmlspecialchars($tx['description']); ?></td>
                        <td><?php echo $tx['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No transactions yet.</p>
    <?php endif; ?>
    
    <p>
        <a href="deposit.php">Deposit Funds</a> | 
        <a href="withdraw.php">Withdraw Funds</a> | 
        <a href="transfer.php">Transfer Funds</a>
    </p>
</main>
<?php include '../includes/footer.php'; ?>
