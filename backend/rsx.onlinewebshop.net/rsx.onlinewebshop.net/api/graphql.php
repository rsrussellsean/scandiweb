<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    require_once __DIR__ . '/../vendor/autoload.php';
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => [
            'message' => 'Failed to load dependencies: ' . $e->getMessage()
        ]
    ]);
    exit;
}

use App\Controller\GraphQL;

try {
    echo GraphQL::handle();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => [
            'message' => 'GraphQL error: ' . $e->getMessage()
        ]
    ]);
}
