<?php

namespace App\Models\Category;

class TechCategory extends Category
{
    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'type' => 'tech',
            'hasWarranty' => true,
            'warrantyPeriod' => '2 years',
            'requiresSpecialHandling' => true
        ];
    }

    public function getApplicableAttributes(): array
    {
        return ['capacity', 'port', 'power'];
    }
}
