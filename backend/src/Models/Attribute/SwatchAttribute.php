<?php
namespace App\Models\Attribute;

class SwatchAttribute extends Attribute
{
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'items' => $this->items
        ];
    }
}
