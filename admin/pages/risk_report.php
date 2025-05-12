<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Only allow access for admin and super_admin.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$riskAnalyzer = new RiskAnalyzer();
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT id, username, email, full_name FROM users ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Compute risk for each user.
$userRiskData = [];
foreach ($users as $user) {
    $risk = $riskAnalyzer->calculateRiskScore($user['id']);
    $userRiskData[] = array_merge($user, $risk);
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>User Risk Report</h2>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Full Name</th>
                <th>Risk Score</th>
                <th>Risk Level</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($userRiskData as $data): ?>
                <tr>
                    <td><?php echo $data['id']; ?></td>
                    <td><?php echo htmlspecialchars($data['username']); ?></td>
                    <td><?php echo htmlspecialchars($data['email']); ?></td>
                    <td><?php echo htmlspecialchars($data['full_name']); ?></td>
                    <td><?php echo $data['score']; ?></td>
                    <td><?php echo ucfirst($data['risk_level']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php include '../includes/footer.php'; ?>
