<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Ensure that only admin or super_admin users may access this page.
if (!isset($_SESSION['user']) || 
    ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'super_admin')) {
    header("Location: login.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['export_type'])) {
    $exportType = $_POST['export_type'];

    $exporter = new DataExport();
    
    switch ($exportType) {
        case 'loans':
            $data = $exporter->getLoansData();
            $headers = ['Loan ID', 'User ID', 'Amount', 'Collateral', 'Status', 'Applied At'];
            $filename = 'loans_export_' . date('Ymd_His') . '.csv';
            break;
        case 'repayments':
            $data = $exporter->getRepaymentsData();
            $headers = ['Repayment ID', 'Loan ID', 'Amount', 'Paid At'];
            $filename = 'repayments_export_' . date('Ymd_His') . '.csv';
            break;
        case 'auctions':
            $data = $exporter->getAuctionsData();
            $headers = ['Auction ID', 'Loan ID', 'Collateral', 'Start Price', 'Current Price', 'Status', 'Created At', 'Auction End'];
            $filename = 'auctions_export_' . date('Ymd_His') . '.csv';
            break;
        case 'users':
            $data = $exporter->getUsersData();
            $headers = ['User ID', 'Username', 'Email', 'Full Name', 'Phone', 'Role', 'Joined'];
            $filename = 'users_export_' . date('Ymd_His') . '.csv';
            break;
        default:
            $message = "Invalid export type selected.";
            $data = [];
    }
    
    if (!$message) {
        // Export the data as CSV.
        $exporter->exportCSV($data, $headers, $filename);
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Export Data</h2>
    <?php if ($message): ?>
        <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="export_type">Select Data to Export:</label>
        <select id="export_type" name="export_type" required>
            <option value="">--Select--</option>
            <option value="loans">Loans</option>
            <option value="repayments">Repayments</option>
            <option value="auctions">Auctions</option>
            <option value="users">Users</option>
        </select>
        <br><br>
        <button type="submit">Export as CSV</button>
    </form>
</main>
<?php include '../includes/footer.php'; ?>
