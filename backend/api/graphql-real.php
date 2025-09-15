<?php
// Real GraphQL endpoint with schema, resolvers, and proper GraphQL processing

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Load the autoloader
require_once '../autoload.php';

use App\GraphQL\SimpleGraphQL;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\OrderRepository;

try {
    // Get the request body
    $input = json_decode(file_get_contents('php://input'), true);

    if (!$input || !isset($input['query'])) {
        throw new Exception('Invalid GraphQL request');
    }

    // Initialize repositories
    $productRepository = new ProductRepository();
    $categoryRepository = new CategoryRepository();
    $orderRepository = new OrderRepository();

    // Create GraphQL context with repositories
    $context = [
        'productRepository' => $productRepository,
        'categoryRepository' => $categoryRepository,
        'orderRepository' => $orderRepository
    ];

    // Initialize the real GraphQL engine
    $graphql = new SimpleGraphQL();

    // Handle introspection queries
    if (strpos($input['query'], '__schema') !== false) {
        $result = $graphql->introspect();
    } else {
        // Execute the GraphQL query with proper parsing and resolution
        $result = $graphql->execute(
            $input['query'],
            $input['variables'] ?? [],
            $context
        );
    }

    echo json_encode($result);

} catch (Exception $e) {
    $response = [
        'errors' => [
            ['message' => $e->getMessage()]
        ]
    ];
    echo json_encode($response);
}
?>