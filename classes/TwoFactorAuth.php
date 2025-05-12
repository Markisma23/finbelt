<?php
class TwoFactorAuth {
    /**
     * Generate a new secret for 2FA.
     *
     * @param int $length Number of characters for the secret (default 16 hexadecimal digits).
     * @return string
     */
    public function generateSecret($length = 16) {
        // Generate random binary data and convert it to a hex string.
        return bin2hex(random_bytes($length / 2));
    }
    
    /**
     * Generate the current TOTP code for a secret.
     *
     * @param string $secret The secret in hexadecimal.
     * @param int|null $timeSlice Optional time slice (defaults to current time / 30).
     * @return string A 6-digit TOTP code.
     */
    public function getCode($secret, $timeSlice = null) {
        if ($timeSlice === null) {
            $timeSlice = floor(time() / 30);
        }
        // Convert the hexadecimal secret to binary.
        $secretKey = pack("H*", $secret);
        // Pack time into binary (8-byte string; first 4 bytes are 0).
        $time = pack("N*", 0) . pack("N*", $timeSlice);
        // Compute HMAC-SHA1 hash.
        $hash = hash_hmac('sha1', $time, $secretKey, true);
        $offset = ord(substr($hash, -1)) & 0x0F;
        $part = substr($hash, $offset, 4);
        $value = unpack("N", $part)[1] & 0x7FFFFFFF;
        $modulo = pow(10, 6);
        return str_pad($value % $modulo, 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Verify a provided 2FA code against a secret.
     *
     * @param string $secret The secret in hexadecimal.
     * @param string $code The TOTP code provided by the user.
     * @param int $discrepancy The allowed time slice discrepancy.
     * @param int|null $currentTimeSlice Optionally provide a time slice.
     * @return bool True if the code is valid.
     */
    public function verifyCode($secret, $code, $discrepancy = 1, $currentTimeSlice = null) {
        if ($currentTimeSlice === null) {
            $currentTimeSlice = floor(time() / 30);
        }
        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            if ($this->getCode($secret, $currentTimeSlice + $i) === $code) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Generate a URL that can be used to create a QR code for Google Authenticator.
     *
     * @param string $secret The secret in hexadecimal.
     * @param string $username The username or identifier.
     * @param string $issuer The name of your service (e.g., Finbelt Microfinance).
     * @return string The otpauth URL.
     */
    public function getQRUrl($secret, $username, $issuer) {
        // Format the URL as:
        // otpauth://totp/{ISSUER}:{USERNAME}?secret={SECRET}&issuer={ISSUER}
        $otpauth = "otpauth://totp/" . urlencode($issuer) . ":" . urlencode($username) .
                   "?secret=" . strtoupper($secret) . "&issuer=" . urlencode($issuer);
        return $otpauth;
    }
}
?>
