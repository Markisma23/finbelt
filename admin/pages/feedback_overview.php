<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure only admin or super_admin can access.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$feedbackObj = new Feedback();
$message = '';

// Process status update if submitted.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_id'], $_POST['new_status'])) {
    $feedbackId = intval($_POST['feedback_id']);
    $newStatus = $_POST['new_status'];
    if ($feedbackObj->updateFeedbackStatus($feedbackId, $newStatus)) {
        $message = "Feedback status updated.";
    } else {
        $message = "Failed to update feedback status.";
    }
}

// Retrieve all feedback entries.
$feedbackList = $feedbackObj->getFeedback();
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Customer Feedback Overview</h2>
    <?php if ($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if (count($feedbackList) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Feedback ID</th>
                    <th>User</th>
                    <th>Rating</th>
                    <th>Comments</th>
                    <th>Status</th>
                    <th>Submitted At</th>
                    <th>Change Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbackList as $fb): ?>
                    <tr>
                        <td><?php echo $fb['id']; ?></td>
                        <td><?php echo htmlspecialchars($fb['username']); ?></td>
                        <td><?php echo $fb['rating']; ?></td>
                        <td><?php echo nl2br(htmlspecialchars($fb['comments'])); ?></td>
                        <td><?php echo ucfirst($fb['status']); ?></td>
                        <td><?php echo $fb['created_at']; ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="feedback_id" value="<?php echo $fb['id']; ?>">
                                <select name="new_status" required>
                                    <option value="">Select</option>
                                    <option value="new" <?php if($fb['status'] == 'new') echo 'selected'; ?>>New</option>
                                    <option value="reviewed" <?php if($fb['status'] == 'reviewed') echo 'selected'; ?>>Reviewed</option>
                                    <option value="responded" <?php if($fb['status'] == 'responded') echo 'selected'; ?>>Responded</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>                
            </tbody>
        </table>
    <?php else: ?>
        <p>No feedback records found.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
