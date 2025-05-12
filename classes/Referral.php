<?php
class Referral {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Generate a unique referral code.
     *
     * @param int $userId
     * @return string Generated referral code.
     */
    public function generateReferralCode($userId) {
        // You can use a combination of user ID and a random component.
        // Here, we use a simple mechanism for demonstration.
        $code = strtoupper("REF" . $userId . substr(md5(uniqid(rand(), true)), 0, 6));
        // Ensure uniqueness.
        $stmt = $this->db->prepare("SELECT id FROM referrals WHERE referral_code = ?");
        $stmt->execute([$code]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)) {
            // If the code exists, recursively generate a new one.
            return $this->generateReferralCode($userId);
        }
        return $code;
    }
    
    /**
     * Record a new referral.
     *
     * @param int $referrerId The user who is referring.
     * @param string|null $referredEmail Optional referred user's email.
     * @return bool|string Returns true on success or an error message.
     */
    public function addReferral($referrerId, $referredEmail = null) {
        // Generate a unique referral code for this referral.
        $code = $this->generateReferralCode($referrerId);
        $stmt = $this->db->prepare("INSERT INTO referrals (referrer_id, referral_code, referred_email) VALUES (?, ?, ?)");
        return $stmt->execute([$referrerId, $code, $referredEmail]) ? true : "Failed to record referral.";
    }
    
    /**
     * Retrieve all referrals made by a user.
     *
     * @param int $referrerId
     * @return array
     */
    public function getReferralsByUser($referrerId) {
        $stmt = $this->db->prepare("SELECT * FROM referrals WHERE referrer_id = ? ORDER BY created_at DESC");
        $stmt->execute([$referrerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Update referral status and reward.
     *
     * @param int $referralId
     * @param string $status The referral status ('pending', 'successful', 'failed')
     * @param float $reward The reward value (if any)
     * @return bool
     */
    public function updateReferral($referralId, $status, $reward = 0) {
        $allowed = ['pending', 'successful', 'failed'];
        if (!in_array($status, $allowed)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE referrals SET status = ?, reward = ? WHERE id = ?");
        return $stmt->execute([$status, $reward, $referralId]);
    }
    
    /**
     * Retrieve a referral by its code.
     *
     * @param string $code
     * @return array|false
     */
    public function getReferralByCode($code) {
        $stmt = $this->db->prepare("SELECT * FROM referrals WHERE referral_code = ?");
        $stmt->execute([$code]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
