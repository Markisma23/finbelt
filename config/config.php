<?php

return [
    'db' => [
        'host' => 'localhost',
        'name' => 'finbelt',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'mail' => [
        'smtp_host' => 'smtp.example.com',
        'smtp_port' => 587,
        'smtp_user' => 'no-reply@example.com',
        'smtp_pass' => 'secure_password',
        'from_email' => 'no-reply@finbelt.com',
        'from_name' => 'Finbelt Microfinance',
    ],
    'app' => [
        'base_url' => 'http://localhost/finbelt',
        'env' => 'development',
        'default_lang' => 'en',
    ],
];
?>