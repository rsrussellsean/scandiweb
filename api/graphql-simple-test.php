<?php
// Simplified GraphQL test endpoint
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    // Test if we can get the request
    $input = file_get_contents('php://input');

    if (!$input) {
        throw new Exception('No input data received');
    }

    $data = json_decode($input, true);

    if (!$data) {
        throw new Exception('Invalid JSON data: ' . json_last_error_msg());
    }

    if (!isset($data['query'])) {
        throw new Exception('No GraphQL query provided');
    }

    // Simple mock response based on query
    $query = $data['query'];
    $response = ['data' => null];

    if (strpos($query, 'categories') !== false) {
        $response['data'] = [
            'categories' => [
                ['name' => 'clothes'],
                ['name' => 'tech'],
                ['name' => 'all']
            ]
        ];
    } elseif (strpos($query, 'products') !== false) {
        $response['data'] = [
            'products' => [
                [
                    'id' => 'test-product-1',
                    'name' => 'Test Product 1',
                    'inStock' => true,
                    'brand' => 'Test Brand',
                    'gallery' => ['https://example.com/image1.jpg'],
                    'description' => 'Test description',
                    'category' => 'clothes',
                    'attributes' => [],
                    'prices' => [
                        [
                            'currency' => ['label' => 'USD', 'symbol' => '$'],
                            'amount' => 99.99
                        ]
                    ]
                ]
            ]
        ];
    } elseif (strpos($query, 'product(') !== false) {
        $response['data'] = [
            'product' => [
                'id' => 'test-product-1',
                'name' => 'Test Product 1',
                'inStock' => true,
                'brand' => 'Test Brand',
                'gallery' => ['https://example.com/image1.jpg'],
                'description' => 'Test description',
                'category' => 'clothes',
                'attributes' => [],
                'prices' => [
                    [
                        'currency' => ['label' => 'USD', 'symbol' => '$'],
                        'amount' => 99.99
                    ]
                ]
            ]
        ];
    } elseif (strpos($query, 'placeOrder') !== false) {
        $response['data'] = [
            'placeOrder' => 'Order placed successfully (test mode)'
        ];
    } else {
        $response['data'] = [
            'message' => 'GraphQL endpoint is working!',
            'received_query' => $query,
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }

    echo json_encode($response, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'errors' => [
            [
                'message' => $e->getMessage(),
                'timestamp' => date('Y-m-d H:i:s'),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]
        ]
    ], JSON_PRETTY_PRINT);
}
?>