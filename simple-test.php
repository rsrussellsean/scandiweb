<?php
// Super simple test to check basic PHP functionality
echo "<h2>Simple Test Results</h2>";
echo "Hello from Awardspace!<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current directory: " . getcwd() . "<br>";
echo "Server time: " . date('Y-m-d H:i:s') . "<br>";

echo "<h3>Files in root directory:</h3>";
$files = scandir('.');
foreach ($files as $file) {
    if ($file !== '.' && $file !== '..') {
        echo "- $file<br>";
    }
}

echo "<h3>Files in api directory:</h3>";
if (is_dir('api')) {
    $apiFiles = scandir('api');
    foreach ($apiFiles as $file) {
        if ($file !== '.' && $file !== '..') {
            echo "- api/$file<br>";
        }
    }
} else {
    echo "❌ api directory not found<br>";
}

echo "<h3>Files in src directory:</h3>";
if (is_dir('src')) {
    function listDirRecursive($dir, $prefix = '')
    {
        $files = scandir($dir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $fullPath = $dir . '/' . $file;
                echo "- " . $prefix . $file;
                if (is_dir($fullPath)) {
                    echo " (directory)<br>";
                    listDirRecursive($fullPath, $prefix . $file . '/');
                } else {
                    echo "<br>";
                }
            }
        }
    }
    listDirRecursive('src', 'src/');
} else {
    echo "❌ src directory not found<br>";
}
?>