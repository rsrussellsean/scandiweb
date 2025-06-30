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
                $total += $item['price'] * $item['quantity'];
            }

            // Insert into orders
            $stmt = $pdo->prepare("INSERT INTO orders (total_amount) VALUES (?)");
            $stmt->execute([$total]);
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
                $item['productId'],
                $item['quantity'],
                $item['price'],
                json_encode($item['selectedAttributes'] ?? [])
            ]);
            }

            $pdo->commit();
            return "Order placed successfully. Order ID: " . $orderId;
        } catch (Exception $e) {
            $pdo->rollBack();
            throw new \Exception("Failed to place order: " . $e->getMessage());
        }
    }
}
