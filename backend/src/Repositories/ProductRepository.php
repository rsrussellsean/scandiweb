<?php

namespace App\Repositories;

use App\Config\Database;
use PDO;

class ProductRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("
            SELECT 
                p.id, p.name, p.in_stock, p.description, p.category, p.brand,
                GROUP_CONCAT(DISTINCT g.image_url) AS gallery,
                pr.amount, pr.currency_label, pr.currency_symbol
            FROM products p
            LEFT JOIN galleries g ON g.product_id = p.id
            LEFT JOIN prices pr ON pr.product_id = p.id
            GROUP BY p.id
        ");

        $products = [];

        while ($row = $stmt->fetch()) {
            $products[] = [
                "id" => $row["id"],
                "name" => $row["name"],
                "inStock" => (bool) $row["in_stock"],
                "description" => $row["description"],
                "category" => $row["category"],
                "brand" => $row["brand"],
                "gallery" => array_map('trim', explode(",", $row["gallery"])),
                "prices" => [
                    [
                        "amount" => (float) $row["amount"],
                        "currency" => [
                            "label" => $row["currency_label"],
                            "symbol" => $row["currency_symbol"]
                        ]
                    ]
                ]
            ];

        }

        return $products;
    }
}
