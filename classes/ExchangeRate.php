<?php
class ExchangeRate {
    private $db;
    // Define the base currency (for example, ZMW).
    public $baseCurrency = 'ZMW';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Retrieve the current conversion rate for a given currency.
     *
     * @param string $currencyCode A three-letter currency code (e.g., USD)
     * @return float|null The conversion rate relative to the base currency, or null if not found.
     */
    public function getRate($currencyCode) {
        $stmt = $this->db->prepare("SELECT rate FROM exchange_rates WHERE currency_code = ?");
        $stmt->execute([strtoupper($currencyCode)]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (float)$result['rate'] : null;
    }

    /**
     * Update (or insert) the exchange rate for a currency.
     *
     * @param string $currencyCode The currency code.
     * @param float $rate The conversion rate relative to the base currency.
     * @return bool
     */
    public function updateRate($currencyCode, $rate) {
        $currencyCode = strtoupper($currencyCode);
        // Check if record exists.
        $stmt = $this->db->prepare("SELECT id FROM exchange_rates WHERE currency_code = ?");
        $stmt->execute([$currencyCode]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($exists) {
            $stmt = $this->db->prepare("UPDATE exchange_rates SET rate = ? WHERE currency_code = ?");
            return $stmt->execute([$rate, $currencyCode]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO exchange_rates (currency_code, rate) VALUES (?, ?)");
            return $stmt->execute([$currencyCode, $rate]);
        }
    }

    /**
     * Convert an amount from the base currency to the target currency.
     *
     * @param float $amount Amount in base currency.
     * @param string $targetCurrency Currency code for target currency.
     * @return float|null Converted amount, or null if target rate is not available.
     */
    public function convertFromBase($amount, $targetCurrency) {
        $rate = $this->getRate($targetCurrency);
        if ($rate === null) {
            return null;
        }
        return $amount * $rate;
    }
    
    /**
     * Convert an amount from one currency to another.
     *
     * @param float $amount The amount in source currency.
     * @param string $sourceCurrency The source currency code.
     * @param string $targetCurrency The target currency code.
     * @return float|null Converted amount or null if rate cannot be determined.
     */
    public function convert($amount, $sourceCurrency, $targetCurrency) {
        $sourceCurrency = strtoupper($sourceCurrency);
        $targetCurrency = strtoupper($targetCurrency);
        
        if ($sourceCurrency === $targetCurrency) {
            return $amount;
        }
        
        // To convert, first bring the source amount to base currency, then to target.
        if ($sourceCurrency === $this->baseCurrency) {
            return $this->convertFromBase($amount, $targetCurrency);
        }
        
        $sourceRate = $this->getRate($sourceCurrency);
        $targetRate = $this->getRate($targetCurrency);
        if ($sourceRate === null || $targetRate === null || $sourceRate == 0) {
            return null;
        }
        $amountInBase = $amount / $sourceRate;
        return $amountInBase * $targetRate;
    }
    
    /**
     * Retrieve all exchange rates.
     *
     * @return array
     */
    public function getAllRates() {
        $stmt = $this->db->query("SELECT currency_code, rate, updated_at FROM exchange_rates ORDER BY currency_code ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
