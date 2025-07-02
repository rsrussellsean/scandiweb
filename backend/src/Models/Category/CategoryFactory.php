<?php

namespace App\Models\Category;

class CategoryFactory
{
    public static function create(int $id, string $name): Category
    {
        return match (strtolower($name)) {
            'clothes' => new ClothesCategory($id, $name),
            'tech' => new TechCategory($id, $name),
            default => new GenericCategory($id, $name)
        };
    }
}
