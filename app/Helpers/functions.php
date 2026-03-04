<?php
// app/Helpers/functions.php

if (!function_exists('csrf_token')) {
    function csrf_token() {
        // Sessiya boshlanganligini tekshirish
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Agar token mavjud bo'lmasa, yangisini yaratish
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }
        
        // Token eskirgan bo'lsa (1 soatdan ko'p), yangilash
        if (time() - ($_SESSION['csrf_token_time'] ?? 0) > 3600) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }
        
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('validate_csrf')) {
    function validate_csrf($token) {
        // Sessiya boshlanganligini tekshirish
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Token mavjudligini tekshirish
        if (empty($_SESSION['csrf_token'])) {
            error_log("CSRF Error: Token mavjud emas");
            return false;
        }
        
        // Token mosligini tekshirish
        if ($token !== $_SESSION['csrf_token']) {
            error_log("CSRF Error: Token mos kelmadi - Received: $token, Expected: " . $_SESSION['csrf_token']);
            return false;
        }
        
        return true;
    }
}
if (!function_exists('time_elapsed_string')) {
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        
        $string = array(
            'y' => 'yil',
            'm' => 'oy',
            'w' => 'hafta',
            'd' => 'kun',
            'h' => 'soat',
            'i' => 'daqiqa',
            's' => 'soniya',
        );
        
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
            } else {
                unset($string[$k]);
            }
        }
        
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' oldin' : 'hozir';
    }
}

// Qolgan helper funksiyalar...