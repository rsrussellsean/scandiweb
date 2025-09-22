<?php
// Error logging test
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>500 Error Debug Tool</h2>";

// Test 1: Basic PHP works
echo "<h3>Test 1: Basic PHP</h3>";
echo "✅ PHP is working if you see this!<br>";
echo "PHP Version: " . phpversion() . "<br>";

// Test 2: Check current directory and files
echo "<h3>Test 2: File System Check</h3>";
echo "Current directory: " . getcwd() . "<br>";
echo "Document root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

echo "<h4>Files in current directory:</h4>";
if (is_readable('.')) {
    $files = scandir('.');
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $type = is_dir($file) ? '[DIR]' : '[FILE]';
            echo "- $type $file<br>";
        }
    }
} else {
    echo "❌ Cannot read current directory<br>";
}

// Test 3: Check specific files we need
echo "<h3>Test 3: Required Files Check</h3>";
$requiredFiles = [
    'autoload.php',
    'src/Config/DatabaseSimple.php',
    'api/graphql-real.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
        if (is_readable($file)) {
            echo "&nbsp;&nbsp;✅ $file is readable<br>";
        } else {
            echo "&nbsp;&nbsp;❌ $file is not readable<br>";
        }
    } else {
        echo "❌ $file missing<br>";
    }
}

// Test 4: Try to include autoload
echo "<h3>Test 4: Autoloader Test</h3>";
if (file_exists('autoload.php')) {
    try {
        require_once 'autoload.php';
        echo "✅ autoload.php included successfully<br>";
    } catch (ParseError $e) {
        echo "❌ Parse error in autoload.php: " . $e->getMessage() . "<br>";
    } catch (Error $e) {
        echo "❌ Fatal error in autoload.php: " . $e->getMessage() . "<br>";
    } catch (Exception $e) {
        echo "❌ Exception in autoload.php: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ autoload.php not found<br>";
}

// Test 5: Test basic class loading
echo "<h3>Test 5: Class Loading Test</h3>";
try {
    if (class_exists('App\Config\DatabaseSimple', true)) {
        echo "✅ DatabaseSimple class loaded<br>";
    } else {
        echo "❌ DatabaseSimple class not found<br>";
    }
} catch (Error $e) {
    echo "❌ Error loading DatabaseSimple: " . $e->getMessage() . "<br>";
}

echo "<h3>🔍 If you see errors above, that's what's causing your 500 error!</h3>";
?>