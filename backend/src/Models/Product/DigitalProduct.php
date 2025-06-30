<?php

namespace App\Models;

class DigitalProduct extends Product
{
    public function getType(): string
    {
        return 'digital';
    }

    public function getAttributes(): array
    {
        return []; 
    }
}
