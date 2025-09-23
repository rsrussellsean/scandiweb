<?php
namespace App\Resolvers;

use App\Models\Attribute\AttributeFactory;

class AttributeResolver
{
    public static function resolve(array $rows): array
    {
        $attributes = [];

        foreach ($rows as $a) {
            $attrName = $a['name'];

            if (!isset($attributes[$attrName])) {
                $attribute = AttributeFactory::create($a['attr_id'], $a['name'], $a['type']);
                $attributes[$attrName] = $attribute;
            }

            $attributes[$attrName]->addItem([
                'id' => $a['item_id'],
                'value' => $a['value'],
                'displayValue' => $a['display_value']
            ]);
        }

return array_values(array_map(fn($attr) => $attr->toArray(), $attributes));
    }
}
