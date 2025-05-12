<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$walletObj = new Wallet();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipient_user_id'], $_POST['amount'])) {
    $recipientUserId = intval($_POST['recipient_user_id']);
    $amount = floatval($_POST['amount']);
    if ($amount <= 0) {
        $message = "Please enter a valid amount.";
    } else {
        $result = $walletObj->transfer($_SESSION['user']['id'], $recipientUserId, $amount, "Fund transfer");
        if ($result === true) {
            $message = "Successfully transferred ZMW " . number_format($amount, 2) . " to user ID " . $recipientUserId . ".";
        } else {
            $message = $result; // Contains error message if any.
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Transfer Funds</h2>
    <?php if($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="recipient_user_id">Recipient User ID:</label>
        <input type="number" name="recipient_user_id" id="recipient_user_id" required>
        <br><br>
        <label for="amount">Amount (ZMW):</label>
        <input type="number" step="0.01" name="amount" id="amount" required>
        <br><br>
        <button type="submit">Transfer</button>
    </form>
    <p><a href="wallet.php">Back to Wallet</a></p>
</main>
<?php include '../includes/footer.php'; ?>
