<?php

namespace App\Models\Category;

class GenericCategory extends Category
{
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
        ];
    }
}

