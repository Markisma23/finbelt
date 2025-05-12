<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$feedbackObj = new Feedback();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'], $_POST['comments'])) {
    $rating = intval($_POST['rating']);
    $comments = trim($_POST['comments']);
    $result = $feedbackObj->submitFeedback($_SESSION['user']['id'], $rating, $comments);
    if ($result === true) {
        $message = "Thank you for your feedback!";
    } else {
        $message = $result;
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Submit Feedback</h2>
    <?php if($message): ?>
        <p style="color:<?php echo (strpos($message, 'Thank you') !== false ? 'green' : 'red'); ?>;">
            <?php echo htmlspecialchars($message); ?>
        </p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="rating">Rating (1-5):</label><br>
        <input type="number" name="rating" id="rating" min="1" max="5" required><br><br>
        
        <label for="comments">Comments:</label><br>
        <textarea name="comments" id="comments" rows="5" cols="50" placeholder="Your feedback here..." required></textarea><br><br>
        
        <button type="submit">Submit Feedback</button>
    </form>
    <br>
    <p><a href="dashboard.php">Return to Dashboard</a></p>
</main>
<?php include '../includes/footer.php'; ?>
