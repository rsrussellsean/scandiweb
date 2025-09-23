<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once __DIR__ . '/../src/Config/Database.php';
require_once __DIR__ . '/../src/Repositories/ProductRepository.php';

use App\Repositories\ProductRepository;

$repo = new ProductRepository();
echo json_encode([
    "data" => [
        "products" => $repo->getAll()
    ]
]);
