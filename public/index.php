<?php

// ✅ Always configure PHP before any output!
ini_set('display_errors', 0); // Don't show errors to users
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);     // Log to file
ini_set('error_log', __DIR__ . '/../error.log'); // Log file path
error_reporting(E_ALL);       // Log all errors

require_once __DIR__ . '/../vendor/autoload.php';

echo "✅ It works!";
