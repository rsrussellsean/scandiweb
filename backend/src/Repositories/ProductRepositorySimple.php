<?php

namespace App\Repositories;

use App\Config\DatabaseSimple;
use PDO;

class ProductRepositorySimple
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseSimple::connect();
    }

    public function getAll(): array
    {
        // Fetch all base product info
        $stmt = $this->pdo->prepare("
            SELECT p.*, 
                   GROUP_CONCAT(DISTINCT pg.image_url) as gallery_images
            FROM products p
            LEFT JOIN product_gallery pg ON p.id = pg.product_id
            GROUP BY p.id
        ");
        $stmt->execute();
        
        $products = [];
        while ($row = $stmt->fetch()) {
            $product = [
                'id' => $row['id'],
                'name' => $row['name'],
                'inStock' => (bool)$row['in_stock'],
                'description' => $row['description'],
                'category' => $row['category'],
                'brand' => $row['brand'],
                'gallery' => $row['gallery_images'] ? explode(',', $row['gallery_images']) : [],
                'prices' => $this->getProductPrices($row['id']),
                'attributes' => $this->getProductAttributes($row['id'])
            ];
            $products[] = $product;
        }

        return $products;
    }

    public function getById(string $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT p.*, 
                   GROUP_CONCAT(DISTINCT pg.image_url) as gallery_images
            FROM products p
            LEFT JOIN product_gallery pg ON p.id = pg.product_id
            WHERE p.id = ?
            GROUP BY p.id
        ");
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        return [
            'id' => $row['id'],
            'name' => $row['name'],
            'inStock' => (bool)$row['in_stock'],
            'description' => $row['description'],
            'category' => $row['category'],
            'brand' => $row['brand'],
            'gallery' => $row['gallery_images'] ? explode(',', $row['gallery_images']) : [],
            'prices' => $this->getProductPrices($row['id']),
            'attributes' => $this->getProductAttributes($row['id'])
        ];
    }

    private function getProductPrices(string $productId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT pp.amount, c.label, c.symbol
            FROM product_prices pp
            JOIN currencies c ON pp.currency_label = c.label
            WHERE pp.product_id = ?
        ");
        $stmt->execute([$productId]);
        
        $prices = [];
        while ($row = $stmt->fetch()) {
            $prices[] = [
                'amount' => (float)$row['amount'],
                'currency' => [
                    'label' => $row['label'],
                    'symbol' => $row['symbol']
                ]
            ];
        }
        
        return $prices;
    }

    private function getProductAttributes(string $productId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT pa.id, pa.name, pa.type,
                   pai.id as item_id, pai.display_value, pai.value
            FROM product_attributes pa
            LEFT JOIN product_attribute_items pai ON pa.id = pai.attribute_id
            WHERE pa.product_id = ?
            ORDER BY pa.id, pai.id
        ");
        $stmt->execute([$productId]);
        
        $attributes = [];
        $currentAttribute = null;
        
        while ($row = $stmt->fetch()) {
            if ($currentAttribute === null || $currentAttribute['id'] !== $row['id']) {
                if ($currentAttribute !== null) {
                    $attributes[] = $currentAttribute;
                }
                $currentAttribute = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'type' => $row['type'],
                    'items' => []
                ];
            }
            
            if ($row['item_id']) {
                $currentAttribute['items'][] = [
                    'id' => $row['item_id'],
                    'displayValue' => $row['display_value'],
                    'value' => $row['value']
                ];
            }
        }
        
        if ($currentAttribute !== null) {
            $attributes[] = $currentAttribute;
        }
        
        return $attributes;
    }
}
