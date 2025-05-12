<?php
class Kyc {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Upload a new KYC document for a user.
     * @param int $userId The ID of the user.
     * @param string $documentType Type or description of the document.
     * @param string $fileName The file name/path where the document is stored.
     * @return bool
     */
    public function uploadDocument($userId, $documentType, $fileName) {
        $stmt = $this->db->prepare("INSERT INTO kyc_documents (user_id, document_type, file_path) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $documentType, $fileName]);
    }
    
    /**
     * Get all documents uploaded by a user.
     * @param int $userId
     * @return array
     */
    public function getUserDocuments($userId) {
        $stmt = $this->db->prepare("SELECT * FROM kyc_documents WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Retrieve pending documents for admin review.
     * @return array
     */
    public function getPendingDocuments() {
        $stmt = $this->db->query("
            SELECT kyc_documents.*, users.username 
            FROM kyc_documents 
            JOIN users ON kyc_documents.user_id = users.id 
            WHERE status = 'pending'
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Update the status of an uploaded document.
     *
     * @param int $documentId
     * @param string $status 'verified' or 'rejected'
     * @param int $reviewerId
     * @return bool
     */
    public function updateDocumentStatus($documentId, $status, $reviewerId) {
        $allowed = ['verified', 'rejected'];
        if (!in_array($status, $allowed)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE kyc_documents SET status = ?, reviewer_id = ?, reviewed_at = NOW() WHERE id = ?");
        return $stmt->execute([$status, $reviewerId, $documentId]);
    }
}
?>
