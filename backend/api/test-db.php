<?php
// Test database connection for Awardspace deployment
require_once __DIR__ . '/../autoload.php';

use App\Config\DatabaseSimple;

try {
    echo "<h2>🔍 Testing Awardspace Database Connection</h2>";

    $pdo = DatabaseSimple::connect();
    echo "<p>✅ <strong>Database connection: SUCCESS</strong></p>";

    // Test categories table
    $stmt = $pdo->query("SELECT id, name FROM categories LIMIT 5");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<p>📁 Categories found: " . count($categories) . "</p>";

    // Test products table
    $stmt = $pdo->query("SELECT id, name, inStock FROM products LIMIT 5");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<p>📦 Products found: " . count($products) . "</p>";

    // Show some data
    echo "<h3>Sample Categories:</h3>";
    echo "<pre>" . json_encode($categories, JSON_PRETTY_PRINT) . "</pre>";

    echo "<h3>Sample Products:</h3>";
    echo "<pre>" . json_encode($products, JSON_PRETTY_PRINT) . "</pre>";

} catch (Exception $e) {
    echo "<h2>❌ Database Connection Failed</h2>";
    echo "<p><strong>Error:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Host:</strong> fdb1033.awardspace.net</p>";
    echo "<p><strong>Database:</strong> 4652023_ecommerce</p>";
}
?>