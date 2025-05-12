<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Only allow admin and super_admin to update exchange rates.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$exchangeRate = new ExchangeRate();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['currency_code'], $_POST['rate'])) {
    $currencyCode = strtoupper(trim($_POST['currency_code']));
    $rate = floatval($_POST['rate']);
    if ($exchangeRate->updateRate($currencyCode, $rate)) {
        $message = "Exchange rate for {$currencyCode} updated successfully.";
    } else {
        $message = "Failed to update exchange rate for {$currencyCode}.";
    }
}

$allRates = $exchangeRate->getAllRates();
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Exchange Rate Management</h2>
    <?php if($message): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="currency_code">Currency Code:</label>
        <input type="text" id="currency_code" name="currency_code" maxlength="3" required>
        
        <label for="rate">Rate (relative to <?php echo $exchangeRate->baseCurrency; ?>):</label>
        <input type="number" step="0.0001" id="rate" name="rate" required>
        
        <button type="submit">Update Rate</button>
    </form>
    <h3>Current Exchange Rates</h3>
    <?php if(count($allRates) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Currency Code</th>
                    <th>Rate (1 <?php echo $exchangeRate->baseCurrency; ?> = ?)</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($allRates as $rate): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($rate['currency_code']); ?></td>
                        <td><?php echo number_format($rate['rate'], 4); ?></td>
                        <td><?php echo $rate['updated_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No exchange rate data available.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
