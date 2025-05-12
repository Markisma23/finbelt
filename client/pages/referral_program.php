<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$referralObj = new Referral();
$userId = $_SESSION['user']['id'];
$message = '';

// If the user wants to generate/refresh their referral code, you could do that here.
// For this example, we simply add a referral record if one does not exist.
$referrals = $referralObj->getReferralsByUser($userId);
if (empty($referrals)) {
    // Create a new referral record for the user.
    $referralObj->addReferral($userId);
    $referrals = $referralObj->getReferralsByUser($userId);
}

// Assume the first referral record holds the user's primary referral code.
$primaryReferral = $referrals[0];
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Referral Program Dashboard</h2>
    <p>Your unique referral code: <strong><?php echo htmlspecialchars($primaryReferral['referral_code']); ?></strong></p>
    <p>Share your referral code with friends. When they sign up or apply for a loan using your referral code, you may earn rewards!</p>
    
    <h3>Your Referrals</h3>
    <?php if (count($referrals) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Referral ID</th>
                    <th>Referral Code</th>
                    <th>Referred Email</th>
                    <th>Status</th>
                    <th>Reward (ZMW)</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($referrals as $ref): ?>
                    <tr>
                        <td><?php echo $ref['id']; ?></td>
                        <td><?php echo htmlspecialchars($ref['referral_code']); ?></td>
                        <td><?php echo htmlspecialchars($ref['referred_email']); ?></td>
                        <td><?php echo ucfirst($ref['status']); ?></td>
                        <td><?php echo number_format($ref['reward'], 2); ?></td>
                        <td><?php echo $ref['created_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have not referred anyone yet.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
