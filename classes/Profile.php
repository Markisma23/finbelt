<?php
class Profile {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Retrieve the full profile information for a user by ID.
     *
     * @param int $userId
     * @return array|false
     */
    public function getProfile($userId) {
        $stmt = $this->db->prepare("SELECT id, username, email, full_name, phone, profile_image FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update profile details for a user.
     *
     * @param int $userId
     * @param string $fullName
     * @param string $phone
     * @param string|null $profileImagePath (if updated)
     * @return bool
     */
    public function updateProfile($userId, $fullName, $phone, $profileImagePath = null) {
        if ($profileImagePath) {
            $stmt = $this->db->prepare("UPDATE users SET full_name = ?, phone = ?, profile_image = ? WHERE id = ?");
            return $stmt->execute([$fullName, $phone, $profileImagePath, $userId]);
        } else {
            $stmt = $this->db->prepare("UPDATE users SET full_name = ?, phone = ? WHERE id = ?");
            return $stmt->execute([$fullName, $phone, $userId]);
        }
    }

    /**
     * Update user password.
     *
     * @param int $userId
     * @param string $newPassword Plain text new password (will be hashed)
     * @return bool
     */
    public function updatePassword($userId, $newPassword) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$hashedPassword, $userId]);
    }
}
?>
