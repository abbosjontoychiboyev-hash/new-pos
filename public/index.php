<?php
// public/index.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Sessiyani boshlash
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Konstantalar
require_once __DIR__ . '/../config/constants.php';

// Database
require_once __DIR__ . '/../config/database.php';

// Helper funksiyalar
require_once APP_PATH . '/Helpers/functions.php';

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = APP_PATH . '/';
    
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    
    if (file_exists($file)) {
        require $file;
    }
});

// Request URI ni olish
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/new-pos'; // Loyiha papkasi nomi

// Base path ni olib tashlash
if (strpos($requestUri, $basePath) === 0) {
    $requestUri = substr($requestUri, strlen($basePath));
}

// GET parametrlarini olib tashlash
$requestUri = strtok($requestUri, '?');

// /public ni olib tashlash
$requestUri = str_replace('/public', '', $requestUri);

// / ni tozalash
$requestUri = trim($requestUri, '/');

// Routing faylini yuklash
$routesFile = ROUTES_PATH . '/web.php';
if (!file_exists($routesFile)) {
    die("Routes file not found: $routesFile");
}

$routes = require $routesFile;
$httpMethod = $_SERVER['REQUEST_METHOD'];

// AJAX tekshirish: agar AJAX bo'lsa, ikkita dispatch kaliti bilan tekshiramiz: 'AJAX' va haqiqiy HTTP method
$isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

$dispatchMethods = $isAjax ? ['AJAX', $httpMethod] : [$httpMethod];

// Debug
error_log("Request URI: " . $_SERVER['REQUEST_URI']);
error_log("Dispatch methods: " . implode(',', $dispatchMethods));

// Route ni ishga tushirish
route_dispatch($routes, $dispatchMethods, $requestUri);

function route_dispatch($routes, array $methods, $uri) {

    // Maxsus hol: bo'sh URI (bosh sahifa)
    if ($uri === '' || $uri === 'index.php') {
        $uri = '';
    }

    foreach ($methods as $method) {
        if (!isset($routes[$method])) {
            // key mavjud emas, navbatdagi methodga o'tamiz
            continue;
        }

        foreach ($routes[$method] as $pattern => $handler) {
        $pattern = trim($pattern, '/');

        // Patternni regexga aylantirish - {param} qismi har qanday alfanumerik va underscore uchun ishlaydi
        $regexPattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([^/]+)', $pattern);
        $regexPattern = '/^' . str_replace('/', '\/', $regexPattern) . '$/';

        if (preg_match($regexPattern, $uri, $matches)) {
            // Birinchi elementni olib tashlash (to'liq match)
            array_shift($matches);
            
            list($controller, $action) = $handler;
            $controllerClass = "App\\Controllers\\" . $controller;
            
            if (!class_exists($controllerClass)) {
                die("Controller topilmadi: $controllerClass");
            }
            
            $controllerInstance = new $controllerClass();
            
            if (!method_exists($controllerInstance, $action)) {
                die("Method topilmadi: $action");
            }
            
            call_user_func_array([$controllerInstance, $action], $matches);
            return;
        }
        }
    }

    // 404 - Sahifa topilmadi
    http_response_code(404);
    echo "404 - Sahifa topilmadi: /" . $uri;
    echo "<br><br>Mavjud route'lar (" . htmlentities(implode(',', $methods)) . "):";
    echo "<ul>";
    // Ko'rsatilgan dispatch methodlar uchun mavjud route'larni chiqaramiz
    foreach ($methods as $m) {
        foreach (array_keys($routes[$m] ?? []) as $route) {
            echo "<li>" . ($route ?: '/') . " (" . htmlentities($m) . ")</li>";
        }
    }
    echo "</ul>";
    echo "<h3>Debug:</h3>";
    echo "ROUTES_PATH: " . ROUTES_PATH . "<br>";
    echo "Routes file exists: " . (file_exists(ROUTES_PATH . '/web.php') ? 'Yes' : 'No') . "<br>";
    echo "Request URI: " . htmlentities($_SERVER['REQUEST_URI']) . "<br>";
    echo "Cleaned URI: " . htmlentities($uri) . "<br>";
    echo "Dispatch methods: " . htmlentities(implode(',', $methods)) . "<br>";
}