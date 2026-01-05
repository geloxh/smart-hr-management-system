<?php
namespace Core\Database;

class Connection {
    private static $pdo = null;

    public static function get(): \PDO {
        if (self::$pdo === null) {
            $dsn = "mysql:host={$_ENV['DB_HOST']};dbname={$_ENV['DB_NAME']}";
            self::$pdo = new \PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASS'], [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
            ]);
        }
        return self::$pdo;
    }
}
