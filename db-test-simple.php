<?php
// Standalone database connection test
header('Content-Type: text/html; charset=utf-8');

echo "<h2>Database Connection Test</h2>";

try {
    $host = 'fdb1033.awardspace.net';
    $dbname = '4652023_ecommerce';
    $username = '4652023_ecommerce';
    $password = 'Dnd{{wU/7QER%4*A';

    echo "Attempting to connect to: $host<br>";
    echo "Database: $dbname<br>";
    echo "Username: $username<br>";

    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 30
    ]);

    echo "<h3>✅ Database Connection Successful!</h3>";

    // Test query
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    echo "Test query result: " . $result['test'] . "<br>";

    // Show tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo "<h3>Tables in database:</h3><ul>";
    foreach ($tables as $table) {
        echo "<li>$table</li>";
    }
    echo "</ul>";

    // Show some sample data
    if (in_array('products', $tables)) {
        echo "<h3>Sample products:</h3>";
        $stmt = $pdo->query("SELECT id, name FROM products LIMIT 3");
        $products = $stmt->fetchAll();
        echo "<ul>";
        foreach ($products as $product) {
            echo "<li>{$product['id']}: {$product['name']}</li>";
        }
        echo "</ul>";
    }

} catch (PDOException $e) {
    echo "<h3>❌ Database Connection Failed:</h3>";
    echo "<pre>Error: " . $e->getMessage() . "</pre>";
    echo "<pre>Code: " . $e->getCode() . "</pre>";

    // Additional debugging
    echo "<h4>Connection Details:</h4>";
    echo "Host: $host<br>";
    echo "Database: $dbname<br>";
    echo "Username: $username<br>";
    echo "Error details: " . print_r($e->errorInfo ?? [], true) . "<br>";

} catch (Exception $e) {
    echo "<h3>❌ General Error:</h3>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}
?>