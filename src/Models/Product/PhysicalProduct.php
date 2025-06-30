<?php

namespace App\Models;

class PhysicalProduct extends Product
{
    protected array $attributes;

    public function __construct(string $id, string $name, float $price, array $attributes)
    {
        parent::__construct($id, $name, $price);
        $this->attributes = $attributes;
    }

    public function getType(): string
    {
        return 'physical';
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }
}
