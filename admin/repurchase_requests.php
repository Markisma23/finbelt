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

require_once __DIR__ . '/../includes/classes/Repurchase.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $requestId = intval($_POST['request_id']);
    $status    = $_POST['status']; // Expected to be 'accepted' or 'declined'
    try {
        if (Repurchase::updateStatus($requestId, $status)) {
            $message = "Request updated successfully.";
        } else {
            $message = "Failed to update request.";
        }
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}

$requests = Repurchase::getAllRequests();

include_once __DIR__ . '/../templates/header.php';
?>
<h2>Manage Repurchase Requests</h2>
<?php if ($message): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
<?php if ($requests): ?>
    <table border="1" cellpadding="5">
        <tr>
            <th>Request ID</th>
            <th>Auction ID</th>
            <th>Client ID</th>
            <th>Offer Price</th>
            <th>Status</th>
            <th>Submitted At</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($requests as $req): ?>
        <tr>
            <td><?php echo $req['id']; ?></td>
            <td><?php echo $req['auction_id']; ?></td>
            <td><?php echo $req['client_id']; ?></td>
            <td><?php echo $req['offered_price']; ?></td>
            <td><?php echo $req['status']; ?></td>
            <td><?php echo $req['created_at']; ?></td>
            <td>
                <?php if ($req['status'] == 'pending'): ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit">Accept</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                        <input type="hidden" name="status" value="declined">
                        <button type="submit">Decline</button>
                    </form>
                <?php else: ?>
                    N/A
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No repurchase requests found.</p>
<?php endif; ?>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
