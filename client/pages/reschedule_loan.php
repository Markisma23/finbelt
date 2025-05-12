<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get the loan ID from the query string.
$loanId = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : 0;
if (!$loanId) {
    echo "Invalid loan specified.";
    exit();
}

$rescheduleObj = new LoanReschedule();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newTerm = intval($_POST['new_term']);
    $newInterestRate = floatval($_POST['new_interest_rate']);
    $reason = trim($_POST['reason']);
    
    if ($rescheduleObj->requestReschedule($loanId, $newTerm, $newInterestRate, $reason) === true) {
        $message = "Reschedule request submitted successfully.";
    } else {
        $message = "Failed to submit reschedule request.";
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Request Loan Reschedule for Loan #<?php echo $loanId; ?></h2>
    <?php if ($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="new_term">New Term (in months):</label><br>
        <input type="number" name="new_term" id="new_term" required><br><br>
        
        <label for="new_interest_rate">Proposed Monthly Interest Rate (e.g., 0.30 for 30%):</label><br>
        <input type="number" step="0.0001" name="new_interest_rate" id="new_interest_rate" required><br><br>
        
        <label for="reason">Reason for Reschedule:</label><br>
        <textarea name="reason" id="reason" rows="4" cols="50" required></textarea><br><br>
        
        <button type="submit">Submit Reschedule Request</button>
    </form>
    <br>
    <p><a href="dashboard.php">Back to Dashboard</a></p>
</main>
<?php include '../includes/footer.php'; ?>
