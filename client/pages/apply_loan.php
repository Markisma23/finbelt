<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure the client is logged in.
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$loanObj = new Loan();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted values.
    $amount = floatval($_POST['amount']);
    $collateral = trim($_POST['collateral']);
    
    // Validation: minimum loan amount (e.g., ZMW 1000).
    if ($amount < 1000) {
        $message = "Loan amount must be at least ZMW 1000.";
    } elseif (empty($collateral)) {
        $message = "Please enter your collateral details.";
    } else {
        // Create the loan application.
        if ($loanObj->applyLoan($_SESSION['user']['id'], $amount, $collateral)) {
            $message = "Your loan application has been submitted successfully. Please await review.";
        } else {
            $message = "Failed to submit your application. Please try again later.";
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Apply for a Loan</h2>
    <?php if ($message): ?>
        <p style="color:<?php echo (strpos($message, 'successfully') !== false) ? 'green' : 'red'; ?>;">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="amount">Loan Amount (ZMW):</label><br>
        <input type="number" step="0.01" name="amount" id="amount" required>
        <br><br>
        
        <label for="collateral">Collateral Details:</label><br>
        <textarea name="collateral" id="collateral" rows="5" cols="50" placeholder="Describe your collateral (e.g., vehicle, equipment)..." required></textarea>
        <br><br>
        
        <button type="submit">Submit Application</button>
    </form>
    <br>
    <p><a href="dashboard.php">Return to Dashboard</a></p>
</main>
<?php include '../includes/footer.php'; ?>
