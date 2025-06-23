<?php

namespace App\Models\Attribute;

abstract class Attribute
{
    protected string $id;
    protected string $name;
    protected string $type;
    protected array $items = [];

    public function __construct(string $id, string $name, string $type)
    {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
    }

    public function addItem(array $item): void
    {
        $this->items[] = $item;
    }

    abstract public function toArray(): array;
}
