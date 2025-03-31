<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}
$user = unserialize($_SESSION['user']);
if ($user->role !== 'admin') {
    header("Location: ../public/login.php");
    exit;
}

require_once __DIR__ . '/../includes/classes/Collateral.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $collateralId = intval($_POST['collateral_id']);
    $status = $_POST['status']; // expected to be 'approved' or 'rejected'
    $appraisalValue = floatval($_POST['appraisal_value']);
    
    try {
        if (Collateral::updateCollateral($collateralId, $status, $appraisalValue)) {
            $message = "Collateral updated successfully.";
        } else {
            $message = "Failed to update collateral.";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$collaterals = Collateral::getAllCollateral();

include_once __DIR__ . '/../templates/header.php';
?>
<h2>Collateral Review</h2>
<?php if ($message): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
<?php if ($collaterals): ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>ID</th>
            <th>Client ID</th>
            <th>Document</th>
            <th>Description</th>
            <th>Expected Value (ZMW)</th>
            <th>Appraisal Value (ZMW)</th>
            <th>Status</th>
            <th>Submitted At</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($collaterals as $c): ?>
        <tr>
            <td><?php echo $c['id']; ?></td>
            <td><?php echo $c['client_id']; ?></td>
            <td>
                <a href="../<?php echo htmlspecialchars($c['document_path']); ?>" target="_blank">View Document</a>
            </td>
            <td><?php echo htmlspecialchars($c['description']); ?></td>
            <td><?php echo $c['expected_value']; ?></td>
            <td><?php echo $c['appraisal_value']; ?></td>
            <td><?php echo $c['status']; ?></td>
            <td><?php echo $c['created_at']; ?></td>
            <td>
                <?php if ($c['status'] == 'pending'): ?>
                <form method="post" style="margin-bottom:5px;">
                    <input type="hidden" name="collateral_id" value="<?php echo $c['id']; ?>">
                    <label>Appraisal Value (ZMW):</label>
                    <input type="number" name="appraisal_value" step="0.01" required>
                    <select name="status">
                        <option value="approved">Approve</option>
                        <option value="rejected">Reject</option>
                    </select>
                    <button type="submit">Update</button>
                </form>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No collateral submissions found.</p>
<?php endif; ?>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
