<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Only allow logged-in admins.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$kyc = new Kyc();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['document_id'], $_POST['action'])) {
    $documentId = intval($_POST['document_id']);
    $action = $_POST['action']; // Expected to be either 'verified' or 'rejected'
    
    if ($kyc->updateDocumentStatus($documentId, $action, $_SESSION['user']['id'])) {
        $message = "Document #$documentId has been updated to status: " . ucfirst($action);
    } else {
        $message = "Failed to update the status for Document #$documentId.";
    }
}

$pendingDocuments = $kyc->getPendingDocuments();
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Review KYC Documents</h2>
    <?php if($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    
    <?php if (count($pendingDocuments) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Document ID</th>
                    <th>User</th>
                    <th>Document Type</th>
                    <th>View Document</th>
                    <th>Uploaded At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pendingDocuments as $doc): ?>
                    <tr>
                        <td><?php echo $doc['id']; ?></td>
                        <td><?php echo htmlspecialchars($doc['username']); ?></td>
                        <td><?php echo htmlspecialchars($doc['document_type']); ?></td>
                        <td>
                            <a href="../../<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank">View</a>
                        </td>
                        <td><?php echo $doc['uploaded_at']; ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="document_id" value="<?php echo $doc['id']; ?>">
                                <select name="action" required>
                                    <option value="">Select</option>
                                    <option value="verified">Verify</option>
                                    <option value="rejected">Reject</option>
                                </select>
                                <button type="submit">Update Status</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No pending documents for review.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
