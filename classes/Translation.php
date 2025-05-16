<?php
namespace App;

class Translation {
    private static $dict = [];

    public static function load(string $lang) {
        $file = __DIR__ . '/../lang/' . $lang . '.php';
        if (file_exists($file)) {
            self::$dict = include $file;
        } else {
            self::$dict = [];
        }
    }

    public static function get(string $key): string {
        return self::$dict[$key] ?? $key;
    }
}

?>