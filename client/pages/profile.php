<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$profile = new Profile();
$userId = $_SESSION['user']['id'];
$userProfile = $profile->getProfile($userId);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Process profile update form.
    $fullName = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $profileImagePath = null;
    
    // Check if a new file is uploaded.
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../uploads/profile_images/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        // For security, validate file type and size here.
        $fileInfo = pathinfo($_FILES['profile_image']['name']);
        $ext = strtolower($fileInfo['extension']);
        $allowed = ['jpg','jpeg','png','gif'];
        if (in_array($ext, $allowed)) {
            $newFileName = 'user_' . $userId . '_' . time() . '.' . $ext;
            $targetFile = $uploadDir . $newFileName;
            if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
                // Save relative path
                $profileImagePath = 'uploads/profile_images/' . $newFileName;
            } else {
                $message = "Failed to upload profile image.";
            }
        } else {
            $message = "Invalid file type. Allowed types: jpg, jpeg, png, gif.";
        }
    }
    
    if (!$message) {
        if ($profile->updateProfile($userId, $fullName, $phone, $profileImagePath)) {
            $message = "Profile updated successfully.";
            // Refresh profile from DB.
            $userProfile = $profile->getProfile($userId);
            // Update session values if needed.
            $_SESSION['user']['full_name'] = $userProfile['full_name'];
        } else {
            $message = "Failed to update profile.";
        }
    }
}
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>Your Profile</h2>
    <?php if($message): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <form method="post" action="" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" value="<?php echo htmlspecialchars($userProfile['username']); ?>" disabled><br><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" value="<?php echo htmlspecialchars($userProfile['email']); ?>" disabled><br><br>
        
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($userProfile['full_name']); ?>" required><br><br>
        
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($userProfile['phone']); ?>"><br><br>
        
        <label for="profile_image">Profile Image:</label>
        <input type="file" id="profile_image" name="profile_image"><br><br>
        <?php if ($userProfile['profile_image']): ?>
            <img src="../../<?php echo htmlspecialchars($userProfile['profile_image']); ?>" alt="Profile Image" width="100"><br><br>
        <?php endif; ?>
        
        <button type="submit">Update Profile</button>
    </form>
    
    <h3>Change Password</h3>
    <p><a href="change_password.php">Click here to change your password</a></p>
</main>
<?php include '../includes/footer.php'; ?>
