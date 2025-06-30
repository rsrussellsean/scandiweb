<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

require_once __DIR__ . '/../vendor/autoload.php';

use App\Repositories\CategoryRepository;

$repo = new CategoryRepository();
$categories = $repo->getAll();

echo json_encode($categories);
