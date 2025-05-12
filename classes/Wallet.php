<?php
class Wallet {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Get the wallet record for a user.
     *
     * @param int $userId
     * @return array|false
     */
    public function getWallet($userId) {
        $stmt = $this->db->prepare("SELECT * FROM wallets WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a wallet for a user.
     *
     * @param int $userId
     * @return bool
     */
    public function createWallet($userId) {
        $stmt = $this->db->prepare("INSERT INTO wallets (user_id, balance) VALUES (?, 0)");
        return $stmt->execute([$userId]);
    }

    /**
     * Credit the wallet by a given amount.
     *
     * @param int $userId
     * @param float $amount
     * @param string|null $description
     * @return bool
     */
    public function credit($userId, $amount, $description = null) {
        // Ensure wallet exists.
        $wallet = $this->getWallet($userId);
        if (!$wallet) {
            $this->createWallet($userId);
            $wallet = $this->getWallet($userId);
        }
        $newBalance = $wallet['balance'] + $amount;
        $stmt = $this->db->prepare("UPDATE wallets SET balance = ? WHERE user_id = ?");
        $result = $stmt->execute([$newBalance, $userId]);
        if ($result) {
            $this->logTransaction($wallet['id'], 'credit', $amount, $description);
        }
        return $result;
    }

    /**
     * Debit the wallet by a given amount.
     *
     * @param int $userId
     * @param float $amount
     * @param string|null $description
     * @return bool|string Returns true if debited, or an error message if insufficient funds.
     */
    public function debit($userId, $amount, $description = null) {
        $wallet = $this->getWallet($userId);
        if (!$wallet) {
            return "Wallet not found for user.";
        }
        if ($wallet['balance'] < $amount) {
            return "Insufficient funds.";
        }
        $newBalance = $wallet['balance'] - $amount;
        $stmt = $this->db->prepare("UPDATE wallets SET balance = ? WHERE user_id = ?");
        $result = $stmt->execute([$newBalance, $userId]);
        if ($result) {
            $this->logTransaction($wallet['id'], 'debit', $amount, $description);
        }
        return $result;
    }

    /**
     * Transfer funds from one user wallet to another.
     *
     * @param int $fromUserId
     * @param int $toUserId
     * @param float $amount
     * @param string|null $description
     * @return bool|string Returns true on success or an error message.
     */
    public function transfer($fromUserId, $toUserId, $amount, $description = null) {
        // Begin a transaction.
        $this->db->beginTransaction();
        $debitResult = $this->debit($fromUserId, $amount, "Transfer to user ID $toUserId. " . $description);
        if ($debitResult !== true) {
            $this->db->rollBack();
            return $debitResult; // Return error message.
        }
        $creditResult = $this->credit($toUserId, $amount, "Transfer from user ID $fromUserId. " . $description);
        if ($creditResult !== true) {
            $this->db->rollBack();
            return "Failed to credit recipient.";
        }
        $this->db->commit();
        return true;
    }

    /**
     * Log a wallet transaction.
     *
     * @param int $walletId
     * @param string $transactionType 'credit' or 'debit'
     * @param float $amount
     * @param string|null $description
     */
    private function logTransaction($walletId, $transactionType, $amount, $description = null) {
        $stmt = $this->db->prepare("INSERT INTO wallet_transactions (wallet_id, transaction_type, amount, description) VALUES (?, ?, ?, ?)");
        $stmt->execute([$walletId, $transactionType, $amount, $description]);
    }

    /**
     * Retrieve wallet transactions for a user.
     *
     * @param int $userId
     * @return array
     */
    public function getTransactions($userId) {
        $wallet = $this->getWallet($userId);
        if (!$wallet) {
            return [];
        }
        $stmt = $this->db->prepare("SELECT * FROM wallet_transactions WHERE wallet_id = ? ORDER BY created_at DESC");
        $stmt->execute([$wallet['id']]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
