<?php
// filepath: c:\Users\russell.s.gonzalve\Documents\scandiweb_revision\scandi2\scandiweb\backend\api\product.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../autoload.php';

use App\Repositories\ProductRepository;

try {
    if (!isset($_GET['id'])) {
        throw new Exception('Product ID is required');
    }

    $productRepository = new ProductRepository();
    $product = $productRepository->getProductById($_GET['id']);

    if (!$product) {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'error' => 'Product not found'
        ]);
        return;
    }

    echo json_encode([
        'success' => true,
        'data' => $product
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}