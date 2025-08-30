<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../vendor/autoload.php';

try {
    // Test GraphQL endpoint
    $query = '
        query {
            products {
                id
                name
                inStock
                category
                prices {
                    amount
                    currency {
                        label
                        symbol
                    }
                }
            }
        }
    ';

    // Test direct GraphQL call
    $result = \App\Controller\GraphQL::handle();

    // Test with actual query
    $postData = json_encode([
        'query' => $query
    ]);

    // Simulate POST request
    $_SERVER['REQUEST_METHOD'] = 'POST';
    file_put_contents('php://temp', $postData);

    echo "GraphQL endpoint test:\n";
    echo "Query: " . $query . "\n\n";

    // Create a test request
    $context = stream_context_create([
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/json',
            'content' => $postData
        ]
    ]);

    // Test our GraphQL controller directly
    echo "Testing GraphQL controller...\n";
    $testResult = \App\Controller\GraphQL::handle();
    echo $testResult;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>