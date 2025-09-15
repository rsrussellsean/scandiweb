<?php
// filepath: c:\Users\russell.s.gonzalve\Documents\scandiweb_revision\scandi2\scandiweb\backend\api\categories.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../autoload.php';

use App\Repositories\CategoryRepository;

try {
    $categoryRepository = new CategoryRepository();
    $categories = $categoryRepository->getAllCategories();

    echo json_encode([
        'success' => true,
        'data' => $categories
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}