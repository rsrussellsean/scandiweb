<?php
date_default_timezone_set('Asia/Manila');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST method is allowed']);
    exit;
}


require_once __DIR__ . '/../src/Config/Database.php';

$pdo = \App\Config\Database::connect();
$data = json_decode(file_get_contents("php://input"), true);

if (!$data || empty($data['items']) || !is_array($data['items'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid order data']);
    exit;
}

try {
    $pdo->beginTransaction();

    // Compute total
    $total = 0;
    foreach ($data['items'] as $item) {
        $total += $item['price']['amount'] * $item['quantity'];
    }

    // Insert into orders
    $createdAt = date('Y-m-d H:i:s'); // Will now be in Asia/Manila timezone
	$stmt = $pdo->prepare("INSERT INTO orders (total_amount, created_at) VALUES (?, ?)");
	$stmt->execute([$total, $createdAt]);
    $orderId = $pdo->lastInsertId();

    // Insert each order item
    $stmt = $pdo->prepare("
        INSERT INTO order_items 
        (order_id, product_id, quantity, price, selected_attributes) 
        VALUES (?, ?, ?, ?, ?)
    ");

    foreach ($data['items'] as $item) {
        $stmt->execute([
            $orderId,
            $item['id'],
            $item['quantity'],
            $item['price']['amount'],
            json_encode($item['selectedAttributes'])
        ]);
    }

    $pdo->commit();

    echo json_encode(['success' => true, 'orderId' => $orderId]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['error' => 'Order failed: ' . $e->getMessage()]);
}
