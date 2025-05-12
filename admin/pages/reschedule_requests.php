<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Check that the user is admin or super_admin.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$rescheduleObj = new LoanReschedule();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['decision'])) {
    $requestId = intval($_POST['request_id']);
    $decision = $_POST['decision'];  // Either 'approved' or 'rejected'
    
    if ($rescheduleObj->updateRescheduleStatus($requestId, $decision)) {
        $message = "Request #{$requestId} has been {$decision}.";
    } else {
        $message = "Failed to update request status.";
    }
}

// Retrieve all pending reschedule requests.
$pendingRequests = $rescheduleObj->getAllRescheduleRequests('pending');
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Loan Reschedule Requests (Pending)</h2>
    <?php if ($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if (count($pendingRequests) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Request ID</th>
                    <th>Loan ID</th>
                    <th>Borrower</th>
                    <th>Loan Amount (ZMW)</th>
                    <th>New Term (Months)</th>
                    <th>Proposed Interest Rate</th>
                    <th>Reason</th>
                    <th>Submitted At</th>
                    <th>Decision</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingRequests as $req): ?>
                    <tr>
                        <td><?php echo $req['id']; ?></td>
                        <td><?php echo $req['loan_id']; ?></td>
                        <td><?php echo htmlspecialchars($req['username']); ?></td>
                        <td>ZMW <?php echo number_format($req['loan_amount'],2); ?></td>
                        <td><?php echo $req['new_term']; ?></td>
                        <td><?php echo $req['new_interest_rate']; ?></td>
                        <td><?php echo htmlspecialchars($req['reason']); ?></td>
                        <td><?php echo $req['created_at']; ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                                <select name="decision" required>
                                    <option value="">Select</option>
                                    <option value="approved">Approve</option>
                                    <option value="rejected">Reject</option>
                                </select>
                                <button type="submit">Submit</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending reschedule requests.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
