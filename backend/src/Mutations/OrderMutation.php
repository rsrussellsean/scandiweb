<?php

namespace App\Mutations;

use App\Config\Database;
use Exception;

class OrderMutation
{
    public static function placeOrder(array $items): string
    {
        $pdo = Database::connect();
        try {
            $pdo->beginTransaction();

            // Calculate total
            $total = 0;
            foreach ($items as $item) {
                $total += $item['price']['amount'] * $item['quantity'];
            }

            // Insert into orders
            $createdAt = date('Y-m-d H:i:s');
            $stmt = $pdo->prepare("INSERT INTO orders (total_amount, created_at) VALUES (?, ?)");
            $stmt->execute([$total, $createdAt]);
            $orderId = $pdo->lastInsertId();

            // Insert order items
            $stmt = $pdo->prepare("
                INSERT INTO order_items 
                (order_id, product_id, quantity, price, selected_attributes) 
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($items as $item) {
                $stmt->execute([
                    $orderId,
                    $item['id'],
                    $item['quantity'],
                    $item['price']['amount'],
                    json_encode($item['selectedAttributes'] ?? [])
                ]);
            }

            $pdo->commit();
            return json_encode(['success' => true, 'orderId' => $orderId]);
        } catch (Exception $e) {
            $pdo->rollBack();
            throw new \Exception("Failed to place order: " . $e->getMessage());
        }
    }
}
