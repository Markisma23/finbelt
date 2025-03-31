<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: ../public/login.php");
    exit;
}
$user = unserialize($_SESSION['user']);
if ($user->role !== 'client') {
    header("Location: ../public/login.php");
    exit;
}

require_once __DIR__ . '/../includes/classes/Collateral.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate file upload
    if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileInfo = pathinfo($_FILES['document']['name']);
        $extension = strtolower($fileInfo['extension']);
        
        if (!in_array($extension, $allowedExtensions)) {
            $message = "Invalid file type. Allowed types: jpg, jpeg, png, pdf.";
        } else {
            // Create a unique file name and move the file
            $uploadDir = __DIR__ . '/../uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $newFileName = uniqid('collateral_', true) . '.' . $extension;
            $destination = $uploadDir . $newFileName;
            
            if (move_uploaded_file($_FILES['document']['tmp_name'], $destination)) {
                // Save the collateral record in the database
                $description = trim($_POST['description']);
                $expectedValue = floatval($_POST['expected_value']);
                
                if (Collateral::submitCollateral($user->id, 'uploads/' . $newFileName, $description, $expectedValue)) {
                    $message = "Collateral submitted successfully.";
                } else {
                    $message = "Failed to submit collateral.";
                }
            } else {
                $message = "Failed to move the uploaded file.";
            }
        }
    } else {
        $message = "Please select a file to upload.";
    }
}

include_once __DIR__ . '/../templates/header.php';
?>
<h2>Upload Collateral</h2>
<?php if ($message): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
<form method="post" action="" enctype="multipart/form-data">
    <label>Collateral Document (jpg, jpeg, png, pdf):</label>
    <input type="file" name="document" required /><br/><br/>
    
    <label>Description:</label>
    <textarea name="description" required></textarea><br/><br/>
    
    <label>Expected Value (ZMW):</label>
    <input type="number" name="expected_value" step="0.01" required /><br/><br/>
    
    <button type="submit">Submit Collateral</button>
</form>
<?php include_once __DIR__ . '/../templates/footer.php'; ?>
