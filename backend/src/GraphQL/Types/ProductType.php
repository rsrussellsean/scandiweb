<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use App\GraphQL\Types\CategoryType;

class ProductType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Product',
            'fields' => function () {
                return [
                    'id' => Type::nonNull(Type::id()),
                    'name' => Type::string(),
                    'description' => Type::string(),
                    'category' => Type::string(), // you can also return nested object of CategoryType
                    'brand' => Type::string(),
                    'inStock' => Type::boolean(),
                    'gallery' => Type::listOf(Type::string()),
                    'price' => [
                        'type' => Type::float(),
                        'resolve' => fn($product) => $product['prices'][0]['amount'] ?? 0
                    ],
                    'currencySymbol' => [
                        'type' => Type::string(),
                        'resolve' => fn($product) => $product['prices'][0]['currency']['symbol'] ?? '$'
                    ],
                    'attributes' => Type::listOf(Type::string()), // Simplified
                ];
            }
        ]);
    }
}
