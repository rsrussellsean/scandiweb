<?php
header('Access-Control-Allow-Origin: http://localhost:8000');
header('Content-Type: text/html; charset=UTF-8');

echo "<h1>Database Connection Test</h1>";
echo "<p>Testing connection to Awardspace database...</p>";

try {
    require_once __DIR__ . '/../src/Config/DatabaseSimple.php';

    $pdo = \App\Config\DatabaseSimple::connect();

    echo "<div style='color: green; font-weight: bold;'>✅ Database connection successful!</div><br>";

    // Test a simple query
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    echo "<p>Test query result: " . $result['test'] . "</p>";

    // Show current database
    $stmt = $pdo->query("SELECT DATABASE() as current_db");
    $result = $stmt->fetch();
    echo "<p>Current database: " . $result['current_db'] . "</p>";

    // List tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    echo "<p>Available tables:</p><ul>";
    foreach ($tables as $table) {
        echo "<li>" . array_values($table)[0] . "</li>";
    }
    echo "</ul>";

} catch (Exception $e) {
    echo "<div style='color: red; font-weight: bold;'>❌ Database connection failed!</div><br>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";

    // Show some debug info
    echo "<h3>Debug Information:</h3>";
    echo "<p>Host: fdb1033.awardspace.net</p>";
    echo "<p>Database: 4652023_ecommerce</p>";
    echo "<p>Username: 4652023_ecommerce</p>";

    // Test DNS resolution
    echo "<h3>DNS Resolution Test:</h3>";
    $ip = gethostbyname('fdb1033.awardspace.net');
    if ($ip !== 'fdb1033.awardspace.net') {
        echo "<p>✅ DNS resolves to: " . $ip . "</p>";
    } else {
        echo "<p>❌ DNS resolution failed</p>";
    }

    // Suggestions
    echo "<h3>Possible Solutions:</h3>";
    echo "<ul>";
    echo "<li>Check if the hostname is correct in DatabaseSimple.php</li>";
    echo "<li>Verify your internet connection</li>";
    echo "<li>Make sure Awardspace database is active</li>";
    echo "<li>Try accessing the database from Awardspace control panel</li>";
    echo "<li>Check Windows firewall settings</li>";
    echo "<li>Try using a different network/internet connection</li>";
    echo "</ul>";
}
?>