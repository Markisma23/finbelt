<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

// Only accessible by admin or super_admin.
if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] !== 'admin' && $_SESSION['user']['role'] !== 'super_admin')) {
    header("Location: login.php");
    exit();
}

// Retrieve all users.
$db = Database::getInstance()->getConnection();
$stmt = $db->query("SELECT id, username, email, full_name, phone, profile_image, role, created_at FROM users ORDER BY id ASC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>User Management</h2>
    <?php if (count($users) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>User ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Full Name</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Profile Image</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['id']; ?></td>
                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                        <td><?php echo htmlspecialchars($user['email']); ?></td>
                        <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($user['phone']); ?></td>
                        <td><?php echo ucfirst($user['role']); ?></td>
                        <td>
                            <?php if ($user['profile_image']): ?>
                                <img src="../../<?php echo htmlspecialchars($user['profile_image']); ?>" width="50">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td><?php echo $user['created_at']; ?></td>
                        <td>
                            <a href="edit_user.php?id=<?php echo $user['id']; ?>">Edit</a>
                            <!-- Optionally add a password reset option, delete user, etc. -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No users found.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
