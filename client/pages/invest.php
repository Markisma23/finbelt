<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$loanId = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : 0;
if (!$loanId) {
    echo "Invalid loan specified.";
    exit();
}

$investmentObj = new Investment();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['amount'])) {
    $amount = floatval($_POST['amount']);
    $result = $investmentObj->invest($_SESSION['user']['id'], $loanId, $amount);
    if ($result === true) {
        $message = "Investment of ZMW " . number_format($amount, 2) . " has been recorded.";
    } else {
        $message = $result;  // Contains error message.
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Invest in Loan #<?php echo $loanId; ?></h2>
    <?php if($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="amount">Investment Amount (ZMW):</label>
        <input type="number" step="0.01" name="amount" id="amount" required>
        <br><br>
        <button type="submit">Invest</button>
    </form>
    <p><a href="invest_opportunities.php">Back to Investment Opportunities</a></p>
</main>
<?php include '../includes/footer.php'; ?>
