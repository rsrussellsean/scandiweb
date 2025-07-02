<?php
// Simple test to check database connection
require_once __DIR__ . '/../vendor/autoload.php';

try {
    $pdo = \App\Config\Database::connect();
    echo "Database connection: SUCCESS\n";

    $stmt = $pdo->query("SELECT id, name FROM categories LIMIT 5");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "Categories found: " . count($categories) . "\n";
    echo "Data: " . json_encode($categories, JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>