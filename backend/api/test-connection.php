<?php
// Test database connection for localhost:8000 setup
header('Access-Control-Allow-Origin: http://localhost:8000');
header('Content-Type: text/html; charset=UTF-8');

echo "<h1>Database Connection Test</h1>";
echo "<p>Testing connection to Awardspace database from localhost:8000...</p>";

try {
    require_once __DIR__ . '/../src/Config/Database.php';

    $pdo = \App\Config\Database::connect();

    echo "<div style='color: green; font-weight: bold;'>✅ Database connection successful!</div><br>";

    // Test products table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Products in database: <strong>" . $result['count'] . "</strong></p>";

    // Test categories table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Categories in database: <strong>" . $result['count'] . "</strong></p>";

    // Test orders table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<p>Orders in database: <strong>" . $result['count'] . "</strong></p>";

    echo "<hr>";
    echo "<h2>GraphQL Endpoint Test</h2>";
    echo "<p>Your GraphQL endpoint is available at:</p>";
    echo "<code>http://localhost:8000/backend/api/graphql.php</code>";

} catch (Exception $e) {
    echo "<div style='color: red; font-weight: bold;'>❌ Database connection failed!</div><br>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check your database configuration in <code>backend/src/Config/Database.php</code></p>";
}
?>