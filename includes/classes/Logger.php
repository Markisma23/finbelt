<?php
class Logger {
    private $logFile;

    public function __construct($logFile = null) {
        // Default log file location (ensure the directory is writable)
        $this->logFile = $logFile ?: __DIR__ . '/../../logs/app.log';
    }

    /**
     * Write a log message with a timestamp.
     */
    public function log($message, $level = 'INFO') {
        $time = date('Y-m-d H:i:s');
        $entry = "[$time] [$level] $message" . PHP_EOL;
        file_put_contents($this->logFile, $entry, FILE_APPEND);
    }
}
