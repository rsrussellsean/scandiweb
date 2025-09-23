<?php

namespace App\Models\Attribute;

class AttributeFactory
{
    public static function create(string $id, string $name, string $type): Attribute
    {
        return match (strtolower($type)) {
            'swatch' => new SwatchAttribute($id, $name, $type),
            default => new TextAttribute($id, $name, $type)
        };
    }
}
