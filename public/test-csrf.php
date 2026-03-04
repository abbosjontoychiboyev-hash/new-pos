<?php
// public/test-csrf.php
session_start();

require_once __DIR__ . '/../app/Helpers/functions.php';

echo "<h1>CSRF Token Test</h1>";

echo "<h3>1. Yangi token yaratish:</h3>";
$token1 = csrf_token();
echo "Token 1: " . $token1 . "<br>";
echo "Session token: " . ($_SESSION['csrf_token'] ?? 'null') . "<br>";

echo "<h3>2. Token validatsiyasi:</h3>";
$result = validate_csrf($token1);
echo "Validate result: " . ($result ? '✅ TO\'G\'RI' : '❌ XATO') . "<br>";

echo "<h3>3. Noto'g'ri token test:</h3>";
$result2 = validate_csrf('wrong-token');
echo "Validate wrong token: " . ($result2 ? '❌ XATO bo\'lishi kerak edi' : '✅ TO\'G\'RI (xato topildi)') . "<br>";

echo "<h3>4. Sessiya ma'lumotlari:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo '<br><a href="test-csrf.php">Qayta yuklash</a>';