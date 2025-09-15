<?php
// filepath: c:\Users\russell.s.gonzalve\Documents\scandiweb_revision\scandi2\scandiweb\backend\api\products.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../autoload.php';

use App\Repositories\ProductRepository;

try {
    $productRepository = new ProductRepository();

    if (isset($_GET['category'])) {
        // For now, just get all products and filter (you can optimize this later)
        $products = $productRepository->getAll();
        if ($_GET['category'] !== 'all') {
            $products = array_filter($products, function ($product) {
                return strtolower($product['category']) === strtolower($_GET['category']);
            });
            $products = array_values($products); // Re-index array
        }
    } else {
        $products = $productRepository->getAll();
    }

    echo json_encode([
        'success' => true,
        'data' => $products
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}