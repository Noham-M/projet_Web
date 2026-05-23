<?php
require_once __DIR__ . '/../../config/db_config.php';

use PDO;

class Database {
    private static ?PDO $pdo = null;

    public static function getPDO(): PDO {
        if (self::$pdo === null) {
            self::$pdo = new PDO(
                "mysql:host=". DB_HOST . ";dbname" "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER,
                DB_PASS,
                [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
            );
        }

        return self::$pdo;
    }
            
}     
    

?>