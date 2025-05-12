<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Only allow admin or super_admin (or a specific role) to update settings.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$settingsObj = new Settings();
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process updates for each setting submitted.
    if (isset($_POST['settings']) && is_array($_POST['settings'])) {
        foreach ($_POST['settings'] as $key => $value) {
            // Optional: also update description if provided.
            $desc = isset($_POST['descriptions'][$key]) ? trim($_POST['descriptions'][$key]) : null;
            $settingsObj->set($key, trim($value), $desc);
        }
        $message = "Settings updated successfully.";
    }
}

// Retrieve all settings to display.
$allSettings = $settingsObj->getAll();
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>System Settings</h2>
    <?php if($message): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Setting Key</th>
                    <th>Setting Value</th>
                    <th>Description</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($allSettings as $setting): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($setting['setting_key']); ?></td>
                        <td>
                            <input type="text" name="settings[<?php echo htmlspecialchars($setting['setting_key']); ?>]" value="<?php echo htmlspecialchars($setting['setting_value']); ?>" size="40">
                        </td>
                        <td>
                            <input type="text" name="descriptions[<?php echo htmlspecialchars($setting['setting_key']); ?>]" value="<?php echo htmlspecialchars($setting['description']); ?>" size="40">
                        </td>
                        <td><?php echo $setting['updated_at']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <button type="submit">Update Settings</button>
    </form>
</main>
<?php include '../includes/footer.php'; ?>
