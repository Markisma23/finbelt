<?php
class Settings {
    private $db;
    private $cache = [];

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Retrieve a setting value by key.
     *
     * @param string $key The setting key.
     * @param mixed $default Optional default value if not set.
     * @return mixed The setting value or $default if not found.
     */
    public function get($key, $default = null) {
        // Use cache if available.
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        $stmt = $this->db->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $this->cache[$key] = $result['setting_value'];
            return $result['setting_value'];
        }
        return $default;
    }

    /**
     * Update or add a setting.
     *
     * @param string $key The setting key.
     * @param string $value The setting value.
     * @param string|null $description Optional description.
     * @return bool
     */
    public function set($key, $value, $description = null) {
        // Check if the setting already exists.
        $stmt = $this->db->prepare("SELECT id FROM settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $exists = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($exists) {
            // Update existing setting.
            $stmt = $this->db->prepare("UPDATE settings SET setting_value = ?, description = ? WHERE setting_key = ?");
            $result = $stmt->execute([$value, $description, $key]);
        } else {
            // Insert new setting.
            $stmt = $this->db->prepare("INSERT INTO settings (setting_key, setting_value, description) VALUES (?, ?, ?)");
            $result = $stmt->execute([$key, $value, $description]);
        }

        if ($result) {
            $this->cache[$key] = $value;
        }
        return $result;
    }

    /**
     * Retrieve all settings.
     *
     * @return array
     */
    public function getAll() {
        $stmt = $this->db->query("SELECT setting_key, setting_value, description, updated_at FROM settings ORDER BY setting_key ASC");
        $settings = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Optionally populate local cache.
        foreach ($settings as $setting) {
            $this->cache[$setting['setting_key']] = $setting['setting_value'];
        }
        return $settings;
    }
}
?>
