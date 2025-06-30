<?php

namespace App\Repositories;

use App\Config\Database;
use PDO;
use App\Models\Attribute\AttributeFactory;


class ProductRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getAll(): array
{
    // Fetch all base product info
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
    $productIds = [];

    while ($row = $stmt->fetch()) {
        $productId = $row["id"];
        $productIds[] = $productId;

        $products[$productId] = [
            "id" => $productId,
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
            ],
            "attributes" => [] 
        ];
    }

    if (count($productIds) === 0) return array_values($products);

    //Fetch all attributes in one go
    $inQuery = implode(",", array_fill(0, count($productIds), "?"));
    $attrStmt = $this->pdo->prepare("
        SELECT a.product_id, a.id AS attr_id, a.name, a.type, 
               ai.item_id, ai.value, ai.display_value
        FROM attributes a
        LEFT JOIN attribute_items ai ON ai.attribute_id = a.id
        WHERE a.product_id IN ($inQuery)
    ");
    $attrStmt->execute($productIds);

    // Map attributes to the correct products
    foreach ($attrStmt->fetchAll() as $row) {
        $productId = $row['product_id'];
        $attrName = $row['name'];

        if (!isset($products[$productId]['attributesAssoc'])) {
            $products[$productId]['attributesAssoc'] = [];
        }

        if (!isset($products[$productId]['attributesAssoc'][$attrName])) {
            $products[$productId]['attributesAssoc'][$attrName] = [
                'id' => $row['attr_id'],
                'name' => $row['name'],
                'type' => $row['type'],
                'items' => []
            ];
        }

        $products[$productId]['attributesAssoc'][$attrName]['items'][] = [
            'id' => $row['item_id'],
            'value' => $row['value'],
            'displayValue' => $row['display_value']
        ];
    }

    // Convert attributesAssoc to plain array
    foreach ($products as &$product) {
        $product['attributes'] = array_values($product['attributesAssoc'] ?? []);
        unset($product['attributesAssoc']);
    }

    return array_values($products);
}


   public function getById(string $id): ?array
{
    $stmt = $this->pdo->prepare("
        SELECT 
            p.id, p.name, p.in_stock, p.description, p.category, p.brand,
            GROUP_CONCAT(DISTINCT g.image_url) AS gallery,
            pr.amount, pr.currency_label, pr.currency_symbol
        FROM products p
        LEFT JOIN galleries g ON g.product_id = p.id
        LEFT JOIN prices pr ON pr.product_id = p.id
        WHERE p.id = ?
        GROUP BY p.id
    ");
    $stmt->execute([$id]);
    $row = $stmt->fetch();

    if (!$row) return null;

    // Fetch attributes
    $attrStmt = $this->pdo->prepare("
        SELECT a.id AS attr_id, a.name, a.type, ai.item_id, ai.value, ai.display_value
        FROM attributes a
        LEFT JOIN attribute_items ai ON ai.attribute_id = a.id
        WHERE a.product_id = ?
    ");
    $attrStmt->execute([$id]);
    $rawAttributes = $attrStmt->fetchAll();

    // Use resolver 
    $attributeArray = \App\Resolvers\AttributeResolver::resolve($rawAttributes);

    return [
        "id" => $row["id"],
        "name" => $row["name"],
        "inStock" => (bool) $row["in_stock"],
        "description" => $row["description"],
        "category" => $row["category"],
        "brand" => $row["brand"],
        "gallery" => array_map('trim', explode(",", $row["gallery"])),
        "prices" => [[
            "amount" => (float) $row["amount"],
            "currency" => [
                "label" => $row["currency_label"],
                "symbol" => $row["currency_symbol"]
            ]
        ]],
        "attributes" => $attributeArray
    ];
}



}
