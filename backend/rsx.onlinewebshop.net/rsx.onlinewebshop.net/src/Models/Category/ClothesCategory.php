<?php

namespace App\Models\Category;

class ClothesCategory extends Category
{
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'type' => 'clothes',
            'hasSize' => true,
            'hasColor' => true,
            'sizingGuide' => 'https://example.com/sizing-guide'
        ];
    }

    public function getApplicableAttributes(): array
    {
        return ['size', 'color', 'material'];
    }
}
