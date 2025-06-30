<?php

namespace App\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connect(): PDO
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "");
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }

        return self::$connection;
    }

    // InfinityFree
//     public static function connect(): PDO
// {
//     if (self::$connection === null) {
//         try {
//             $host = 'sql107.infinityfree.com'; 
//             $dbname = 'if0_39312241_ecommercedb'; 
//             $username = 'if0_39312241'; 
//             $password = 'Ev6HhnnuJ72K0y'; 

//             self::$connection = new PDO(
//                 "mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4",
//                 $username,
//                 $password
//             );
//             self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//             self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
//         } catch (PDOException $e) {
//             die("Database connection failed: " . $e->getMessage());
//         }
//     }

//     return self::$connection;
// }


    // public static function connect(): PDO
    // {
    //     if (self::$connection === null) {
    //         try {
    //             $host = 'fdb1033.awardspace.net'; 
    //             $dbname = '4652023_ecommerce'; 
    //             $username = '4652023_ecommerce'; 
    //             $password = 'Dnd{{wU/7QER%4*A'; 

    //             self::$connection = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    //             self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //             self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    //         } catch (PDOException $e) {
    //             die("Database connection failed: " . $e->getMessage());
    //         }
    //     }

    //     return self::$connection;
    // }
}
