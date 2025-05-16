<?php
$directories = [
__DIR__ . '/config',
__DIR__ . '/core',
__DIR__ . '/public/css',
__DIR__ . '/public/js',
__DIR__ . '/public/uploads',
__DIR__ . '/admin',
__DIR__ . '/admin/includes',
__DIR__ . '/admin/controllers',
__DIR__ . '/admin/views',
__DIR__ . '/client',
__DIR__ . '/client/includes',
__DIR__ . '/client/controllers',
__DIR__ . '/client/views',
__DIR__ . '/languages',
__DIR__ . '/backups',
__DIR__ . '/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        if (mkdir($dir, 0755, true)) {
            echo "Created directory: $dir" . PHP_EOL;
        } else {
            echo "Failed to create directory: $dir" . PHP_EOL;
        }
    } else {
        echo "Directory already exists: $dir" . PHP_EOL;
    }
}

?>