<?php

namespace App\Models\Category;

class CategoryFactory
{
    public static function create(int $id, string $name): Category
    {
        return new GenericCategory($id, $name);
    }
}
