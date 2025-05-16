<?php
spl_autoload_register(function ($class) {
    $baseDir = __DIR__ . '/../';
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) require_once $file;
});

$GLOBALS['config'] = require __DIR__ . '/config.php';
if (session_status() === PHP_SESSION_NONE) session_start();


?>