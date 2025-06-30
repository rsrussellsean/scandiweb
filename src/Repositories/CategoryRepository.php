<?php

namespace App\Repositories;

use App\Config\Database;
use App\Models\Category\CategoryFactory;
use PDO;

class CategoryRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::connect();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT id, name FROM categories");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($rows as $row) {
            $category = CategoryFactory::create((int)$row['id'], $row['name']);
            $categories[] = $category->toArray(); 
        }

        return $categories;
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT id, name FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) return null;

        $category = CategoryFactory::create((int)$row['id'], $row['name']);
        return $category->toArray();
    }
}
