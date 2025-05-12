<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$riskAnalyzer = new RiskAnalyzer();
$userId = $_SESSION['user']['id'];
$riskData = $riskAnalyzer->calculateRiskScore($userId);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Your Risk Profile</h2>
    <p><strong>Risk Score:</strong> <?php echo $riskData['score']; ?> (out of 100)</p>
    <p><strong>Risk Level:</strong> <?php echo ucfirst($riskData['risk_level']); ?></p>
    <?php if ($riskData['risk_level'] === 'high'): ?>
        <p style="color: red;">Your current risk level is high. Please consider improving your repayment history or providing additional collateral to help lower your risk profile.</p>
    <?php elseif ($riskData['risk_level'] === 'medium'): ?>
        <p style="color: orange;">Your risk level is medium. Focus on maintaining timely repayments to lower your risk further.</p>
    <?php else: ?>
        <p style="color: green;">Great work! Your risk level is low.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
