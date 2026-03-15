<?php
// config/constants.php

// Vaqt mintaqasi
date_default_timezone_set('Asia/Tashkent');

// Asosiy yo'llar
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('ROUTES_PATH', ROOT_PATH . '/routes');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');

// Environment-based URLs
define('BASE_URL', getenv('APP_URL') ?: 'http://localhost:8000');
define('BASE_PATH', getenv('APP_BASE_PATH') ?: '/new-pos'); // Loyiha papkasi nomi

// Xavfsizlik
define('BCRYPT_COST', 12);
define('SESSION_LIFETIME', 7200); // 2 soat

// Pagination
define('ITEMS_PER_PAGE', 20);