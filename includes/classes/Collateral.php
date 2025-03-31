<?php
require_once 'Database.php';

class Collateral {
    /**
     * Submit a collateral record.
     *
     * @param int $clientId
     * @param string $documentPath
     * @param string $description
     * @param float $expectedValue
     * @return bool
     */
    public static function submitCollateral($clientId, $documentPath, $description, $expectedValue) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("INSERT INTO collateral (client_id, document_path, description, expected_value)
                               VALUES (:client_id, :document_path, :description, :expected_value)");
        return $stmt->execute([
            'client_id'      => $clientId,
            'document_path'  => $documentPath,
            'description'    => $description,
            'expected_value' => $expectedValue
        ]);
    }
    
    /**
     * Retrieve all collateral submissions for a given client.
     *
     * @param int $clientId
     * @return array
     */
    public static function getCollateralByClient($clientId) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM collateral WHERE client_id = :client_id ORDER BY created_at DESC");
        $stmt->execute(['client_id' => $clientId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Retrieve all collateral records (for admin review).
     *
     * @return array
     */
    public static function getAllCollateral() {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM collateral ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Update collateral status and appraisal value.
     *
     * @param int $collateralId
     * @param string $status ('approved' or 'rejected')
     * @param float $appraisalValue
     * @return bool
     */
    public static function updateCollateral($collateralId, $status, $appraisalValue) {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("UPDATE collateral SET status = :status, appraisal_value = :appraisal_value WHERE id = :id");
        return $stmt->execute([
            'status'          => $status,
            'appraisal_value' => $appraisalValue,
            'id'              => $collateralId
        ]);
    }
}
