<?php
// config/config.php

require_once __DIR__ . '/constants.php';

// Sessiyani boshlash (agar boshlanmagan bo'lsa)
if (session_status() === PHP_SESSION_NONE) {
    // Sessiya nomini sozlash
    session_name('POS_SESSION');
    
    // Sessiya parametrlarini sozlash
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Agar HTTPS bo'lsa 1 qiling
    
    session_start();
}

// .env faylini yuklash (agar mavjud bo'lsa)
if (file_exists(ROOT_PATH . '/.env')) {
    $lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_ENV[trim($key)] = trim($value);
            putenv("$key=$value");
        }
    }
}

// Error reporting
if (getenv('APP_ENV') === 'development' || true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}