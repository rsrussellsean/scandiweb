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
            'items' => array_map(function ($item) {
                // Add specific text behavior - ensure proper formatting
                if (isset($item['displayValue'])) {
                    $item['formattedValue'] = $this->formatTextValue($item['displayValue']);
                }
                return $item;
            }, $this->items)
        ];
    }

    private function formatTextValue(string $value): string
    {
        // Capitalize first letter and ensure consistent spacing
        return ucfirst(trim(strtolower($value)));
    }
}
