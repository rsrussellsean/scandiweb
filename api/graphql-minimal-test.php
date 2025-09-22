<?php
// Super minimal GraphQL test - no dependencies
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Just return a simple response without any includes
echo json_encode([
    'data' => [
        'message' => 'Minimal GraphQL endpoint working!',
        'timestamp' => date('Y-m-d H:i:s'),
        'php_version' => phpversion()
    ]
]);
?>