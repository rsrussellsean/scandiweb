<?php
// Test GraphQL locally without network restrictions
require_once __DIR__ . '/../vendor/autoload.php';

try {
    // Simulate a GraphQL query locally
    $controller = new \App\Controller\GraphQL();

    // Test categories query
    $query = '{"query":"{ categories { id name } }"}';

    // Simulate POST data
    $_POST = json_decode($query, true);

    // Mock php://input
    $GLOBALS['test_input'] = $query;

    echo "Testing GraphQL Categories Query:\n";
    echo "Query: " . $query . "\n\n";

    // Test database connection first
    $pdo = \App\Config\Database::connect();
    echo "Database connection: SUCCESS\n";

    $stmt = $pdo->query("SELECT id, name FROM categories LIMIT 5");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Categories found: " . count($categories) . "\n";
    echo "Raw data: " . json_encode($categories, JSON_PRETTY_PRINT) . "\n\n";

    // Test repository
    $repo = new \App\Repositories\CategoryRepository();
    $processedCategories = $repo->getAll();

    echo "Processed categories: " . json_encode($processedCategories, JSON_PRETTY_PRINT) . "\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>