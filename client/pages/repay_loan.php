<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$loanId = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : 0;
if ($loanId <= 0) {
    echo "Invalid loan selection.";
    exit();
}

$loanObj = new Loan();
$repaymentObj = new Repayment();

// Get the loan details for the user.
$userLoans = $loanObj->getUserLoans($_SESSION['user']['id']);
$loanFound = false;
foreach ($userLoans as $loan) {
    if ($loan['id'] == $loanId) {
        $loanFound = $loan;
        break;
    }
}

if (!$loanFound) {
    echo "You are not authorized to view this loan.";
    exit();
}

$paymentMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['repayment_amount'])) {
    $repaymentAmount = floatval($_POST['repayment_amount']);

    // Process payment via PaymentGateway.
    $paymentGateway = new PaymentGateway();
    $result = $paymentGateway->processPayment($repaymentAmount, $_SESSION['user']['id'], $loanId);
    
    if ($result['success']) {
        if ($repaymentObj->makeRepayment($loanId, $repaymentAmount)) {
            $paymentMessage = $result['message'] . " Transaction ID: " . $result['transaction_id'];
            // Log the payment event.
            $audit = new AuditLog();
            $audit->logEvent($_SESSION['user']['id'], 'payment', "Payment of ZMW " . number_format($repaymentAmount,2) . " made for Loan #{$loanId}. Transaction ID: " . $result['transaction_id']);
        } else {
            $paymentMessage = "Payment processed, but failed to record the transaction.";
        }
    }}

$repayments = $repaymentObj->getLoanRepayments($loanId);
$totalRepaid = $repaymentObj->calculateTotalRepayments($loanId);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Loan Repayment for Loan #<?php echo htmlspecialchars($loanFound['id']); ?></h2>
    <p>Loan Amount: ZMW <?php echo number_format($loanFound['amount'], 2); ?></p>
    <p>Status: <?php echo ucfirst($loanFound['status']); ?></p>
    <p>Total Repaid: ZMW <?php echo number_format($totalRepaid, 2); ?></p>
    <?php if($paymentMessage): ?>
        <p style="color:green;"><?php echo htmlspecialchars($paymentMessage); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="repayment_amount">Repayment Amount (ZMW):</label>
        <input type="number" step="0.01" name="repayment_amount" id="repayment_amount" required>
        <button type="submit">Pay Now</button>
    </form>
    <h3>Repayment History</h3>
    <?php if (count($repayments) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Repayment ID</th>
                    <th>Amount (ZMW)</th>
                    <th>Paid At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($repayments as $repayment): ?>
                    <tr>
                        <td><?php echo $repayment['id']; ?></td>
                        <td><?php echo number_format($repayment['amount'], 2); ?></td>
                        <td><?php echo $repayment['paid_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No repayments have been recorded yet.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
