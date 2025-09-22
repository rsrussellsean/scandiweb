<?php
// Enhanced autoloader for Awardspace deployment

spl_autoload_register(function ($className) {
    // Convert namespace to file path
    $className = ltrim($className, '\\');
    $fileName = '';

    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }

    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    // Handle App namespace - map to src directory
    if (strpos($fileName, 'App' . DIRECTORY_SEPARATOR) === 0) {
        $fileName = 'src' . DIRECTORY_SEPARATOR . substr($fileName, 4);
    }

    // Try different base paths for flexibility
    $basePaths = [
        __DIR__ . DIRECTORY_SEPARATOR,
        dirname(__FILE__) . DIRECTORY_SEPARATOR,
        // For when files are in root directory on Awardspace
        '',
        './'
    ];

    foreach ($basePaths as $basePath) {
        $fullPath = $basePath . $fileName;
        if (file_exists($fullPath)) {
            require_once $fullPath;
            return;
        }
    }

    // Debug logging (only in development)
    if (function_exists('error_log')) {
        error_log("Autoloader: Could not load class $className, tried: " . $fileName);
    }
});
