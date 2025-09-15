<?php
// Temporary GraphQL-like endpoint without composer dependencies
header('Access-Control-Allow-Origin: http://localhost:8000');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Include necessary files manually
require_once __DIR__ . '/../src/Config/DatabaseSimple.php';
require_once __DIR__ . '/../src/Repositories/ProductRepositorySimple.php';
require_once __DIR__ . '/../src/Repositories/CategoryRepositorySimple.php';

use App\Config\DatabaseSimple;
use App\Repositories\ProductRepositorySimple;
use App\Repositories\CategoryRepositorySimple;

try {
    // Get the request body
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['query'])) {
        throw new Exception('Invalid GraphQL request');
    }

    $query = $input['query'];
    $variables = $input['variables'] ?? [];

    $response = ['data' => null];

    // Simple query parsing (temporary solution)
    if (strpos($query, 'categories') !== false) {
        // Categories query
        $repo = new CategoryRepositorySimple();
        $categories = $repo->getAll();
        $response['data'] = ['categories' => $categories];

    } elseif (strpos($query, 'product(') !== false && isset($variables['id'])) {
        // Single product query
        $repo = new ProductRepositorySimple();
        $product = $repo->getById($variables['id']);
        $response['data'] = ['product' => $product];

    } elseif (strpos($query, 'products') !== false) {
        // Products query
        $repo = new ProductRepositorySimple();
        $products = $repo->getAll();
        $response['data'] = ['products' => $products];

    } elseif (strpos($query, 'placeOrder') !== false && isset($variables['items'])) {
        // Place order mutation
        $pdo = DatabaseSimple::connect();

        try {
            $pdo->beginTransaction();

            // Calculate total
            $total = 0;
            foreach ($variables['items'] as $item) {
                $total += $item['price'] * $item['quantity'];
            }

            // Insert into orders
            $stmt = $pdo->prepare("INSERT INTO orders (total_amount, created_at) VALUES (?, NOW())");
            $stmt->execute([$total]);
            $orderId = $pdo->lastInsertId();

            // Insert order items
            $stmt = $pdo->prepare("
                INSERT INTO order_items 
                (order_id, product_id, quantity, price, selected_attributes) 
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($variables['items'] as $item) {
                $stmt->execute([
                    $orderId,
                    $item['productId'],
                    $item['quantity'],
                    $item['price'],
                    json_encode($item['selectedAttributes'] ?? [])
                ]);
            }

            $pdo->commit();
            $response['data'] = ['placeOrder' => "Order placed successfully. Order ID: " . $orderId];

        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }

    } else {
        throw new Exception('Unknown GraphQL query');
    }

    echo json_encode($response);

} catch (Exception $e) {
    $response = [
        'errors' => [
            ['message' => $e->getMessage()]
        ]
    ];
    echo json_encode($response);
}
?>