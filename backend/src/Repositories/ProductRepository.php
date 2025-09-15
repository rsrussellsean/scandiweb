<?php

namespace App\Repositories;

use App\Config\DatabaseSimple;
use PDO;
use App\Models\Attribute\AttributeFactory;


class ProductRepository
{
    private ?PDO $pdo;

    public function __construct()
    {
        try {
            $this->pdo = DatabaseSimple::connect();
        } catch (\Exception $e) {
            // Fallback if database connection fails
            $this->pdo = null;
        }
    }

    public function getAll(): array
    {
        // If database connection failed, return mock data
        if ($this->pdo === null) {
            return [
                [
                    'id' => 'huarache-x-stussy-le',
                    'name' => 'Nike Air Huarache Le',
                    'inStock' => true,
                    'gallery' => ['https://cdn.shopify.com/s/files/1/0087/6193/3920/products/DD1381200_DEOA_2_720x.jpg'],
                    'description' => 'Great sneakers for everyday use!',
                    'category' => 'clothes',
                    'brand' => 'Nike x Stussy',
                    'attributes' => [
                        [
                            'id' => 'Size',
                            'name' => 'Size',
                            'type' => 'text',
                            'items' => [
                                ['id' => '40', 'displayValue' => '40', 'value' => '40'],
                                ['id' => '41', 'displayValue' => '41', 'value' => '41']
                            ]
                        ]
                    ],
                    'prices' => [
                        [
                            'currency' => ['label' => 'USD', 'symbol' => '$'],
                            'amount' => 144.69
                        ]
                    ]
                ],
                [
                    'id' => 'jacket-canada-goosee',
                    'name' => 'Jacket',
                    'inStock' => true,
                    'gallery' => ['https://images.canadagoose.com/image/upload/w_480,c_scale,f_auto,q_auto:best/v1576016105/product-image/2409L_61.jpg'],
                    'description' => 'Awesome winter jacket',
                    'category' => 'clothes',
                    'brand' => 'Canada Goose',
                    'attributes' => [
                        [
                            'id' => 'Size',
                            'name' => 'Size',
                            'type' => 'text',
                            'items' => [
                                ['id' => 'S', 'displayValue' => 'Small', 'value' => 'S'],
                                ['id' => 'M', 'displayValue' => 'Medium', 'value' => 'M']
                            ]
                        ]
                    ],
                    'prices' => [
                        [
                            'currency' => ['label' => 'USD', 'symbol' => '$'],
                            'amount' => 518.47
                        ]
                    ]
                ]
            ];
        }

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

        if (count($productIds) === 0)
            return array_values($products);

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
        // If database connection failed, return mock data
        if ($this->pdo === null) {
            $mockProducts = $this->getAll();
            foreach ($mockProducts as $product) {
                if ($product['id'] === $id) {
                    return $product;
                }
            }
            return null;
        }

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

        if (!$row)
            return null;

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
            "prices" => [
                [
                    "amount" => (float) $row["amount"],
                    "currency" => [
                        "label" => $row["currency_label"],
                        "symbol" => $row["currency_symbol"]
                    ]
                ]
            ],
            "attributes" => $attributeArray
        ];
    }



}
