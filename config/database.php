<?php
// config/database.php

class Database {
    private static $connection = null;

    public static function getInstance() {
        if (self::$connection === null) {
            try {
                // Environment variables with fallbacks
                $host = getenv('DB_HOST') ?: 'localhost';
                $dbname = getenv('DB_NAME') ?: 'pos_magazin_uz';
                $user = getenv('DB_USER') ?: 'root';
                $pass = getenv('DB_PASS') ?: '';

                $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

                self::$connection = new PDO($dsn, $user, $pass);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (PDOException $e) {
                die("Database xatosi: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}