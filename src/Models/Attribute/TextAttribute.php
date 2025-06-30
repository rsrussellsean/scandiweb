<?php

namespace App\Models\Attribute;

class TextAttribute extends Attribute
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
