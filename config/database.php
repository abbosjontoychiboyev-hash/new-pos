<?php
// config/database.php - ODDIY VERSIYA

class Database {
    private static $connection = null;
    
    public static function getInstance() {
        if (self::$connection === null) {
            try {
                $host = 'localhost';
                $dbname = 'pos_magazin_uz';
                $user = 'root';
                $pass = '';
                
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