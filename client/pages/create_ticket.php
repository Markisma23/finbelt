<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$ticketObj = new SupportTicket();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['subject'], $_POST['message'])) {
    $subject = trim($_POST['subject']);
    $ticketMessage = trim($_POST['message']);
    if ($ticketObj->createTicket($_SESSION['user']['id'], $subject, $ticketMessage)) {
        $message = "Support ticket created successfully.";
    } else {
        $message = "Failed to create ticket. Please try again.";
    }
}

?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Create Support Ticket</h2>
    <?php if($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="subject">Subject:</label><br>
        <input type="text" id="subject" name="subject" required><br><br>
        
        <label for="message">Message:</label><br>
        <textarea id="message" name="message" rows="5" required></textarea><br><br>
        
        <button type="submit">Submit Ticket</button>
    </form>
    
    <h3>Your Tickets</h3>
    <?php 
        $tickets = $ticketObj->getTicketsByUser($_SESSION['user']['id']);
        if (count($tickets) > 0):
    ?>
        <ul>
            <?php foreach ($tickets as $ticket): ?>
                <li>
                    <a href="view_ticket.php?id=<?php echo $ticket['id']; ?>">
                        [Ticket #<?php echo $ticket['id']; ?>] <?php echo htmlspecialchars($ticket['subject']); ?>
                    </a> - Status: <?php echo ucfirst($ticket['status']); ?> (Created: <?php echo $ticket['created_at']; ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No support tickets created yet.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
