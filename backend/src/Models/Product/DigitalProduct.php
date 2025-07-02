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
        return []; // Digital products typically have no physical attributes
    }

    public function canBeShipped(): bool
    {
        return false; // Digital products are delivered electronically
    }

    public function calculateShippingCost(string $destination): float
    {
        return 0.0; // No shipping cost for digital products
    }

    public function getDownloadLink(): string
    {
        // Generate secure download link for digital products
        return "https://secure-download.example.com/products/{$this->id}";
    }
}
