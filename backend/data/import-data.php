<?php

$pdo = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
$pdo->exec("TRUNCATE TABLE attribute_items");
$pdo->exec("TRUNCATE TABLE attributes");
$pdo->exec("TRUNCATE TABLE prices");
$pdo->exec("TRUNCATE TABLE galleries");
$pdo->exec("TRUNCATE TABLE products");
$pdo->exec("TRUNCATE TABLE categories");
$pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

$json = file_get_contents("data.json");
$data = json_decode($json, true);

// Insert categories
$categories = $data['data']['categories'];

foreach ($categories as $c) {
    $stmt = $pdo->prepare("INSERT IGNORE INTO categories (name) VALUES (?)");
    $stmt->execute([$c['name']]);
}

// Insert products
$products = $data['data']['products'];

foreach ($products as $p) {
    $stmt = $pdo->prepare("INSERT INTO products (id, name, in_stock, description, category, brand) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $p['id'],
        $p['name'],
        $p['inStock'] ?? true,
        $p['description'],
        $p['category'],
        $p['brand']
    ]);

foreach ($p['gallery'] as $img) {
    $stmt = $pdo->prepare("INSERT INTO galleries (product_id, image_url) VALUES (?, ?)");
    $stmt->execute([$p['id'], $img]);
}



    // Insert prices
    foreach ($p['prices'] as $price) {
        $stmt = $pdo->prepare("INSERT INTO prices (product_id, amount, currency_label, currency_symbol) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $p['id'],
            $price['amount'],
            $price['currency']['label'],
            $price['currency']['symbol']
        ]);
    }

    // Insert attributes
    foreach ($p['attributes'] as $attr) {
        $stmt = $pdo->prepare("INSERT INTO attributes (product_id, attr_id, name, type) VALUES (?, ?, ?, ?)");
        $stmt->execute([$p['id'], $attr['id'], $attr['name'], $attr['type']]);

        $attributeId = $pdo->lastInsertId();

        foreach ($attr['items'] as $item) {
            $stmt = $pdo->prepare("INSERT INTO attribute_items (attribute_id, item_id, value, display_value) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $attributeId,
                $item['id'],
                $item['value'],
                $item['displayValue']
            ]);
        }
    }
}

echo "Import complete!";
