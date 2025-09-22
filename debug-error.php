<?php
// Debug file to check what's causing 500 errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>PHP Error Debug</h2>";

// Test 1: Basic PHP
echo "✅ PHP is working<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current directory: " . getcwd() . "<br>";

// Test 2: Check if files exist
echo "<h3>File Checks:</h3>";
$files = [
    'autoload.php',
    'src/Config/DatabaseSimple.php',
    'src/GraphQL/SimpleGraphQL.php',
    'api/graphql-real.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        echo "✅ File exists: $file<br>";
    } else {
        echo "❌ File missing: $file<br>";
    }
}

// Test 3: Try to include autoloader
echo "<h3>Autoloader Test:</h3>";
try {
    require_once 'autoload.php';
    echo "✅ Autoloader loaded successfully<br>";
} catch (Exception $e) {
    echo "❌ Autoloader error: " . $e->getMessage() . "<br>";
}

// Test 4: Try to use DatabaseSimple
echo "<h3>Database Test:</h3>";
try {
    if (class_exists('App\Config\DatabaseSimple')) {
        echo "✅ DatabaseSimple class found<br>";
        $pdo = App\Config\DatabaseSimple::connect();
        echo "✅ Database connection successful<br>";
    } else {
        echo "❌ DatabaseSimple class not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "<br>";
}

// Test 5: Directory listing
echo "<h3>Files in current directory:</h3>";
$files = scandir('.');
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        echo "- $file<br>";
    }
}
?>