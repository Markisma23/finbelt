<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Only allow admin or super_admin access.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$loanId = isset($_GET['loan_id']) ? intval($_GET['loan_id']) : 0;
if (!$loanId) {
    echo "Invalid loan specified.";
    exit();
}

$message = '';
$repaymentSchedule = new RepaymentSchedule();

// For simplicity, suppose an admin can set a new term and a monthly interest rate.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['term_months'], $_POST['monthly_interest_rate'])) {
    $termMonths = intval($_POST['term_months']);
    $monthlyInterestRate = floatval($_POST['monthly_interest_rate']);
    // Retrieve the loan amount from the loans table.
    $stmt = Database::getInstance()->getConnection()->prepare("SELECT amount FROM loans WHERE id = ?");
    $stmt->execute([$loanId]);
    $loan = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($loan) {
        $principal = floatval($loan['amount']);
        $repaymentSchedule->generateSchedule($loanId, $principal, $monthlyInterestRate, $termMonths);
        $message = "Repayment schedule regenerated successfully.";
    } else {
        $message = "Loan not found.";
    }
}

// Retrieve the current schedule.
$schedule = $repaymentSchedule->getSchedule($loanId);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Manage Repayment Schedule for Loan #<?php echo $loanId; ?></h2>
    <?php if ($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="term_months">Term (Months):</label>
        <input type="number" name="term_months" id="term_months" value="12" required>
        <br><br>
        <label for="monthly_interest_rate">Monthly Interest Rate (e.g., 0.35 for 35%):</label>
        <input type="number" step="0.01" name="monthly_interest_rate" id="monthly_interest_rate" value="0.35" required>
        <br><br>
        <button type="submit">Regenerate Schedule</button>
    </form>
    <h3>Current Repayment Schedule</h3>
    <?php if (count($schedule) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Installment</th>
                    <th>Due Date</th>
                    <th>Principal Due (ZMW)</th>
                    <th>Interest Due (ZMW)</th>
                    <th>Total Due (ZMW)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedule as $installment): ?>
                    <tr>
                        <td><?php echo $installment['installment_number']; ?></td>
                        <td><?php echo $installment['due_date']; ?></td>
                        <td><?php echo number_format($installment['principal_due'],2); ?></td>
                        <td><?php echo number_format($installment['interest_due'],2); ?></td>
                        <td><?php echo number_format($installment['total_due'],2); ?></td>
                        <td><?php echo ucfirst($installment['status']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No repayment schedule available.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
