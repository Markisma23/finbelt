<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$walletObj = new Wallet();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'])) {
    $amount = floatval($_POST['amount']);
    if ($amount <= 0) {
        $message = "Please enter a valid amount.";
    } else {
        $result = $walletObj->debit($_SESSION['user']['id'], $amount, "Withdrawal to bank account");
        if ($result === true) {
            $message = "Successfully withdrew ZMW " . number_format($amount, 2) . ".";
        } else {
            $message = $result; // May contain an error message like "Insufficient funds."
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Withdraw Funds</h2>
    <?php if($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="amount">Amount (ZMW):</label>
        <input type="number" step="0.01" name="amount" id="amount" required>
        <br><br>
        <button type="submit">Withdraw</button>
    </form>
    <p><a href="wallet.php">Back to Wallet</a></p>
</main>
<?php include '../includes/footer.php'; ?>
