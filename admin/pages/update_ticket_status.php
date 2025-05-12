<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure only admins have access.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'super_admin')) {
    header("Location: login.php");
    exit();
}

$ticketId = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$ticketId) {
    echo "Invalid ticket specified.";
    exit();
}

$ticketObj = new SupportTicket();
$ticket = $ticketObj->getTicket($ticketId);
if (!$ticket) {
    echo "Ticket not found.";
    exit();
}

$messageFeedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
    $status = $_POST['status'];
    if ($ticketObj->updateTicketStatus($ticketId, $status)) {
        $messageFeedback = "Ticket status updated successfully.";
    } else {
        $messageFeedback = "Failed to update ticket status.";
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Update Ticket Status</h2>
    <?php if($messageFeedback): ?>
        <p style="color:green;"><?php echo htmlspecialchars($messageFeedback); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="status">Select New Status:</label>
        <select name="status" id="status" required>
            <option value="open" <?php if($ticket['status'] === 'open') echo 'selected'; ?>>Open</option>
            <option value="in_progress" <?php if($ticket['status'] === 'in_progress') echo 'selected'; ?>>In Progress</option>
            <option value="closed" <?php if($ticket['status'] === 'closed') echo 'selected'; ?>>Closed</option>
        </select>
        <br><br>
        <button type="submit">Update Status</button>
    </form>
</main>
<?php include '../includes/footer.php'; ?>
