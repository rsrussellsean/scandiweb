<?php

namespace App\Models;

abstract class Product
{
    protected string $id;
    protected string $name;
    protected float $price;

    public function __construct(string $id, string $name, float $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    abstract public function getType(): string;
    abstract public function getAttributes(): array;
    abstract public function canBeShipped(): bool;
    abstract public function calculateShippingCost(string $destination): float;

    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getPrice(): float
    {
        return $this->price;
    }
}
