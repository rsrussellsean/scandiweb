<?php

namespace App\Repositories;

use App\Config\DatabaseSimple;
use PDO;

class CategoryRepositorySimple
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = DatabaseSimple::connect();
    }

    public function getAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];
        foreach ($rows as $row) {
            $categories[] = [
                'id' => $row['id'],
                'name' => $row['name']
            ];
        }

        return $categories;
    }

    public function getById(string $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return [
            'id' => $row['id'],
            'name' => $row['name']
        ];
    }
}
