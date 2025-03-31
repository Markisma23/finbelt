<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}
$user = unserialize($_SESSION['user']);
if ($user->role !== 'client') {
    header("Location: ../public/login.php");
    exit;
}

require_once __DIR__ . '/../includes/classes/LoanRepayment.php';
require_once __DIR__ . '/../includes/classes/Loan.php';

$message = '';
// For demonstration, we assume the client is repaying loan with id passed as a GET parameter
$loanId = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : 0;

// Retrieve the loan details (if any)
$loan = $loanId ? Loan::getLoanById($loanId) : null;

// Process repayment form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $loanId = intval($_POST['loan_id']);
    $paymentAmount = floatval($_POST['payment_amount']);
    $remainingBalance = floatval($_POST['remaining_balance']);
    
    try {
        if (LoanRepayment::recordRepayment($loanId, $user->id, $paymentAmount, $remainingBalance)) {
            $message = "Repayment recorded successfully.";
        } else {
            $message = "Failed to record repayment.";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

// Get repayment history for this loan
$repayments = $loanId ? LoanRepayment::getRepaymentsByLoan($loanId) : [];

include_once __DIR__ . '/../templates/header.php';
?>
<h2>Loan Repayment</h2>
<?php if ($message): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<?php if ($loan): ?>
    <h3>Loan Details</h3>
    <p>Loan ID: <?php echo $loan['id']; ?></p>
    <p>Loan Amount: <?php echo $loan['amount']; ?></p>
    <p>Status: <?php echo $loan['status']; ?></p>
    
    <h3>Submit a Repayment</h3>
    <form method="post" action="">
        <input type="hidden" name="loan_id" value="<?php echo $loan['id']; ?>">
        <label>Payment Amount:</label>
        <input type="number" name="payment_amount" step="0.01" required /><br/>
        
        <label>Remaining Balance (after payment):</label>
        <input type="number" name="remaining_balance" step="0.01" required /><br/>
        
        <button type="submit">Submit Payment</button>
    </form>
    
    <h3>Repayment History</h3>
    <?php if ($repayments): ?>
        <table border="1" cellpadding="5">
            <tr>
                <th>Payment ID</th>
                <th>Payment Amount</th>
                <th>Remaining Balance</th>
                <th>Payment Date</th>
            </tr>
            <?php foreach ($repayments as $repayment): ?>
            <tr>
                <td><?php echo $repayment['id']; ?></td>
                <td><?php echo $repayment['payment_amount']; ?></td>
                <td><?php echo $repayment['remaining_balance']; ?></td>
                <td><?php echo $repayment['payment_date']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No repayments recorded yet.</p>
    <?php endif; ?>
<?php else: ?>
    <p>No loan selected. Please provide a valid loan ID in the URL, e.g., <code>?loan_id=1</code>.</p>
<?php endif; ?>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
