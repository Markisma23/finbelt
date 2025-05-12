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

$contractGenerator = new LoanContractGenerator();
$contract = $contractGenerator->getContract($loanId);

// If no contract exists, generate one.
if (!$contract) {
    $filePath = $contractGenerator->generateContract($loanId);
    $contract = $contractGenerator->getContract($loanId);
}
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['sign_contract'])) {
    // Mark the contract as signed.
    if ($contractGenerator->markAsSigned($contract['id'])) {
        $message = "Contract has been signed successfully.";
        // Optionally, email or notify the admin that the contract has been signed.
    } else {
        $message = "Failed to sign the contract. Please try again.";
    }
    // Refresh contract details.
    $contract = $contractGenerator->getContract($loanId);
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Loan Contract for Loan #<?php echo $loanId; ?></h2>
    <?php if ($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if ($contract): ?>
        <p><a href="../../<?php echo htmlspecialchars($contract['contract_path']); ?>" target="_blank">View Contract PDF</a></p>
        <p>Status: <strong><?php echo ucfirst($contract['status']); ?></strong></p>
        <?php if ($contract['status'] == 'pending'): ?>
            <form method="post" action="">
                <button type="submit" name="sign_contract">I Agree & Sign Contract</button>
            </form>
        <?php else: ?>
            <p>Contract signed on: <?php echo $contract['signed_at']; ?></p>
        <?php endif; ?>
    <?php else: ?>
        <p>Contract not available.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
