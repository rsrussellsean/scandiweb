<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/Config/Database.php';
require_once __DIR__ . '/../src/Repositories/ProductRepository.php';

use App\Repositories\ProductRepository;

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing product ID"]);
    exit;
}

$repo = new ProductRepository();
$product = $repo->getById($_GET['id']);

if (!$product) {
    http_response_code(404);
    echo json_encode(["error" => "Product not found"]);
    exit;
}

echo json_encode($product);
