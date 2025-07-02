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

    public function canBeShipped(): bool
    {
        return true; // Physical products can always be shipped
    }

    public function calculateShippingCost(string $destination): float
    {
        // Base shipping cost calculation for physical products
        $baseCost = 10.0;
        $weightFactor = 0.5; // Assume some weight-based calculation

        return $baseCost + ($this->price * 0.05); // 5% of product price + base cost
    }
}
