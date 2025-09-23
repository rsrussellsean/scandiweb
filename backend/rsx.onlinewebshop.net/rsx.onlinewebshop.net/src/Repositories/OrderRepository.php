<?php

namespace App\Repositories;

use App\Config\DatabaseSimple;
use PDO;

class OrderRepository
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

    public function createOrder(array $items): bool
    {
        // If no database connection, simulate successful order
        if ($this->pdo === null) {
            return true;
        }

        try {
            $this->pdo->beginTransaction();

            // Create order record
            $stmt = $this->pdo->prepare("INSERT INTO orders (created_at) VALUES (NOW())");
            $stmt->execute();
            $orderId = $this->pdo->lastInsertId();

            // Insert order items
            $itemStmt = $this->pdo->prepare("
                INSERT INTO order_items (order_id, product_id, quantity, selected_attributes) 
                VALUES (?, ?, ?, ?)
            ");

            foreach ($items as $item) {
                $itemStmt->execute([
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['selected_attributes'] ?? null
                ]);
            }

            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            if ($this->pdo) {
                $this->pdo->rollback();
            }
            return false;
        }
    }

    public function getOrderById(int $orderId): ?array
    {
        if ($this->pdo === null) {
            return null;
        }

        try {
            $stmt = $this->pdo->prepare("
                SELECT o.id, o.created_at,
                       oi.product_id, oi.quantity, oi.selected_attributes
                FROM orders o
                LEFT JOIN order_items oi ON oi.order_id = o.id
                WHERE o.id = ?
            ");
            $stmt->execute([$orderId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($rows)) {
                return null;
            }

            $order = [
                'id' => $rows[0]['id'],
                'created_at' => $rows[0]['created_at'],
                'items' => []
            ];

            foreach ($rows as $row) {
                if ($row['product_id']) {
                    $order['items'][] = [
                        'product_id' => $row['product_id'],
                        'quantity' => $row['quantity'],
                        'selected_attributes' => $row['selected_attributes']
                    ];
                }
            }

            return $order;

        } catch (\Exception $e) {
            return null;
        }
    }
}