<?php

namespace Config;
class Config {
    public const DB_HOST = 'localhost';
    public const DB_NAME = 'finbelt';
    public const DB_USER = 'root';
    public const DB_PASS = '';
    public const MAIL_FROM = 'no-reply@finbelt.com';
    public const INTEREST_MONTHLY = 0.35;
    public const INTEREST_WEEKLY = 0.10;
    public const BACKUP_PATH = __DIR__ . '/../backups/';
    public const REMINDER_SCHEDULE = [10,5,2,1]; // days before due
    public static $LANGUAGES = [
    'en'=> 'English',
    'fr'=>'French',
    'es'=>'Español',
    'sw'=>'Swahili',
    'ny'=>'Nyanja',
    'bm'=>'Bemba'];
}
?>