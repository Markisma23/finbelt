<?php
class Admin extends User {

    /**
     * Create a new admin account with permission-based access.
     */
    public function createAdmin($username, $email, $password, $permissions = []) {
        // Role for admin is 'admin'. Super admin can then manage permission levels.
        $result = $this->register($username, $email, $password, 'admin');
        if ($result) {
            // Here you can store additional admin permission details in another table (eg. admin_permissions)
            // For simplicity, you could serialize permissions and store them in a JSON column
            // or create another table linking admin ID and permissions.
            return true;
        }
        return false;
    }

    /**
     * Database backup function (accessible only to super admins)
     */
    public function backupDatabase() {
        // This is a simplified demo. In production you might use mysqldump or similar utilities.
        $db = Database::getInstance()->getConnection();
        $tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
        $backup = "";
        foreach ($tables as $table) {
            $stmt = $db->query("SELECT * FROM $table");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $backup .= "-- Data for table: $table\n";
            foreach ($rows as $row) {
                $backup .= json_encode($row) . "\n";
            }
            $backup .= "\n";
        }
        // Save the backup to a file (ideally in a secure directory)
        file_put_contents(__DIR__ . '/../backup_' . time() . '.sql', $backup);
        return true;
    }
}
?>
