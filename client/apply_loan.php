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
require_once __DIR__ . '/../includes/classes/Client.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = floatval($_POST['amount']);
    $collateral = trim($_POST['collateral']);
    try {
        if ($user->applyForLoan($amount, $collateral)) {
            $message = "Loan application submitted successfully.";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
include_once __DIR__ . '/../templates/header.php';
?>
<h2>Apply for a Loan</h2>
<?php if($message) echo "<p>$message</p>"; ?>
<form method="post" action="">
    <label>Loan Amount (Minimum ZMW 1000):</label>
    <input type="number" name="amount" min="1000" required /><br/>
    
    <label>Collateral Details:</label>
    <textarea name="collateral" required></textarea><br/>
    
    <button type="submit">Submit Application</button>
</form>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
