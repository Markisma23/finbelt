<?php
namespace App;

class Backup {
    public static function run() {
        $date = date('Ymd_His');
        $file = \Config\Config::BACKUP_PATH . "backup_{$date}.sql";
        if (!file_exists(\Config\Config::BACKUP_PATH)) {
            mkdir(\Config\Config::BACKUP_PATH, 0755, true);
        }
        $cmd = sprintf(
            'mysqldump -h%s -u%s -p%s %s > %s',
            escapeshellarg(\Config\Config::DB_HOST),
            escapeshellarg(\Config\Config::DB_USER),
            escapeshellarg(\Config\Config::DB_PASS),
            escapeshellarg(\Config\Config::DB_NAME),
            escapeshellarg($file)
        );
        exec($cmd, $output, $returnVar);
        if ($returnVar !== 0) {
            throw new \Exception('Backup failed');
        }
        return $file;
    }
}
