<?php

namespace App\Config;

use PDO;
use PDOException;

class DatabaseSimple
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection === null) {
            try {
                // Use your actual Awardspace credentials
                $host = 'fdb1033.awardspace.net';
                $dbname = '4652023_ecommerce';
                $username = '4652023_ecommerce';
                $password = 'Dnd{{wU/7QER%4*A';

                $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
                self::$connection = new PDO($dsn, $username, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_TIMEOUT => 30, // 30 second timeout for remote connection
                ]);

                // Test the connection
                self::$connection->query("SELECT 1");

            } catch (PDOException $e) {
                throw new PDOException("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}