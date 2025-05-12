<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$kyc = new Kyc();
$uploadMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['kyc_file'])) {
    // Define a secure upload directory.
    $uploadDir = __DIR__ . '/../../uploads/kyc/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    // Basic validations (file type, size, etc. can be added here)
    $file = $_FILES['kyc_file'];
    $documentType = trim($_POST['document_type']);
    $targetFile = $uploadDir . basename($file['name']);
    
    // Move the uploaded file to our uploads directory.
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        // In production, store a relative path or a secure reference instead of the full path.
        if ($kyc->uploadDocument($_SESSION['user']['id'], $documentType, 'uploads/kyc/' . basename($file['name']))) {
            $uploadMessage = "Document uploaded successfully and is pending verification.";
        } else {
            $uploadMessage = "Failed to record document upload in the system.";
        }
    } else {
        $uploadMessage = "Failed to upload your file. Please try again.";
    }
}

$userDocuments = $kyc->getUserDocuments($_SESSION['user']['id']);
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>KYC Document Upload</h2>
    <?php if($uploadMessage): ?>
        <p style="color:green;"><?php echo htmlspecialchars($uploadMessage); ?></p>
    <?php endif; ?>
    
    <form method="post" action="" enctype="multipart/form-data">
        <label for="document_type">Document Type (e.g., ID, Address Proof):</label>
        <input type="text" name="document_type" id="document_type" required>
        
        <label for="kyc_file">Choose Document:</label>
        <input type="file" name="kyc_file" id="kyc_file" required>
        
        <button type="submit">Upload Document</button>
    </form>
    
    <h3>Your Uploaded Documents</h3>
    <?php if (count($userDocuments) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Document ID</th>
                    <th>Type</th>
                    <th>File Path</th>
                    <th>Status</th>
                    <th>Uploaded At</th>
                    <th>Reviewed At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($userDocuments as $doc): ?>
                    <tr>
                        <td><?php echo $doc['id']; ?></td>
                        <td><?php echo htmlspecialchars($doc['document_type']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($doc['file_path']); ?>" target="_blank">View Document</a></td>
                        <td><?php echo ucfirst($doc['status']); ?></td>
                        <td><?php echo $doc['uploaded_at']; ?></td>
                        <td><?php echo $doc['reviewed_at'] ? $doc['reviewed_at'] : 'Not Reviewed'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No documents uploaded yet.</p>
    <?php endif; ?>
</main>
<?php include '../includes/footer.php'; ?>
