<?php
// filepath: c:\Users\russell.s.gonzalve\Documents\scandiweb_revision\scandi2\scandiweb\backend\api\place_order.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

require_once '../autoload.php';

use App\Repositories\OrderRepository;

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Only POST method allowed');
    }

    $input = json_decode(file_get_contents('php://input'), true);

    if (!isset($input['items']) || !is_array($input['items'])) {
        throw new Exception('Items array is required');
    }

    $orderRepository = new OrderRepository();
    $success = $orderRepository->createOrder($input['items']);

    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => 'Order placed successfully'
        ]);
    } else {
        throw new Exception('Failed to create order');
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}