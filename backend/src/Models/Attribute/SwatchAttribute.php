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
            'items' => array_map(function ($item) {
                // Add specific swatch behavior - ensure hex color format
                if (isset($item['value']) && $this->isColorValue($item['value'])) {
                    $item['hexValue'] = $this->normalizeColor($item['value']);
                }
                return $item;
            }, $this->items)
        ];
    }

    private function isColorValue(string $value): bool
    {
        return preg_match('/^#[0-9A-F]{6}$/i', $value) ||
            in_array(strtolower($value), ['red', 'blue', 'green', 'black', 'white']);
    }

    private function normalizeColor(string $value): string
    {
        $colorMap = [
            'red' => '#FF0000',
            'blue' => '#0000FF',
            'green' => '#00FF00',
            'black' => '#000000',
            'white' => '#FFFFFF'
        ];

        return $colorMap[strtolower($value)] ?? $value;
    }
}
