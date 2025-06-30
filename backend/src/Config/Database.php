<?php

namespace App\Config;

use PDO;
use PDOException;
use Dotenv\Dotenv;

class Database
{
    private static ?PDO $connection = null;

    public static function connect(string $connectionType = 'awardspace'): PDO
    {
        if (self::$connection === null) {
            // Load .env
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();

            try {
                if ($connectionType === 'localhost') {
                    $host = $_ENV['DB_LOCAL_HOST'];
                    $dbname = $_ENV['DB_LOCAL_NAME'];
                    $username = $_ENV['DB_LOCAL_USER'];
                    $password = $_ENV['DB_LOCAL_PASS'];
                } elseif ($connectionType === 'infinityfree') {
                    $host = $_ENV['DB_IF_HOST'];
                    $dbname = $_ENV['DB_IF_NAME'];
                    $username = $_ENV['DB_IF_USER'];
                    $password = $_ENV['DB_IF_PASS'];
                } else { // default: awardspace
                    $host = $_ENV['DB_AS_HOST'];
                    $dbname = $_ENV['DB_AS_NAME'];
                    $username = $_ENV['DB_AS_USER'];
                    $password = $_ENV['DB_AS_PASS'];
                }

                self::$connection = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $username,
                    $password
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }
}
