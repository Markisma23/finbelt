<?php
require_once __DIR__ . '/../bootstrap.php';
use App\Database;
requireRole('client');

$u = currentUser();
$db = Database::getInstance()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update name, NRC, country
    $stmt = $db->prepare("UPDATE clients SET name = ?, nrc = ?, country = ? WHERE user_id = ?");
    $stmt->execute([$_POST['name'], $_POST['nrc'], $_POST['country'], $u->id]);
    // Store language in session (or extend DB to persist it)
    $_SESSION['lang'] = $_POST['language'];
    header('Location: profile.php?updated=1');
    exit;
}

// Fetch current client info
$stmt = $db->prepare("SELECT name, nrc, country FROM clients WHERE user_id = ?");
$stmt->execute([$u->id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>
<div class="container mt-4">
  <h2>My Profile</h2>
  <?php if (isset($_GET['updated'])): ?>
    <div class="alert alert-success">Profile updated successfully.</div>
  <?php endif; ?>
  <form method="post">
    <div class="mb-3">
      <label>Name</label>
      <input name="name" value="<?= htmlspecialchars($client['name']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>NRC Number</label>
      <input name="nrc" value="<?= htmlspecialchars($client['nrc']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Country</label>
      <input name="country" value="<?= htmlspecialchars($client['country']) ?>" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Preferred Language</label>
      <select name="language" class="form-select">
        <?php foreach (\Config\Config::$LANGUAGES as $code => $label): ?>
          <option value="<?= $code ?>" <?= ($_SESSION['lang'] ?? 'en') === $code ? 'selected' : '' ?>>
            <?= htmlspecialchars($label) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-primary">Save Changes</button>
  </form>
</div>
<?php include 'includes/footer.php'; ?>
