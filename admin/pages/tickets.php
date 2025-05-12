<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure the user is an admin or super_admin.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'super_admin')) {
    header("Location: login.php");
    exit();
}

$ticketObj = new SupportTicket();
$tickets = $ticketObj->getAllTickets();
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>All Support Tickets</h2>
    <?php if (count($tickets) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Subject</th>
                    <th>Client</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tickets as $ticket): ?>
                    <tr>
                        <td><?php echo $ticket['id']; ?></td>
                        <td><?php echo htmlspecialchars($ticket['subject']); ?></td>
                        <td><?php echo htmlspecialchars($ticket['username']); ?></td>
                        <td><?php echo ucfirst($ticket['status']); ?></td>
                        <td><?php echo $ticket['created_at']; ?></td>
                        <td>
                            <a href="view_ticket.php?id=<?php echo $ticket['id']; ?>">View / Reply</a> |
                            <a href="update_ticket_status.php?id=<?php echo $ticket['id']; ?>">Update Status</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No support tickets found.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
