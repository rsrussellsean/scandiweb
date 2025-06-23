<?php

namespace App\GraphQL\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use App\GraphQL\Inputs\OrderInputType;
use GraphQL\Type\Definition\InputObjectType;
class TypeRegistry
{

    private static ?InputObjectType $orderInput = null;
    private static ?ObjectType $product = null;
    private static ?ObjectType $category = null;

    public static function orderInput(): InputObjectType
    {
    if (self::$orderInput) return self::$orderInput;

    self::$orderInput = new OrderInputType();

    return self::$orderInput;
    }

    public static function product(): ObjectType
    {
        if (self::$product) return self::$product;

        self::$product = new ObjectType([
            'name' => 'Product',
            'fields' => [
                'id' => Type::nonNull(Type::id()),
                'name' => Type::nonNull(Type::string()),
                'inStock' => Type::boolean(),
                'description' => Type::string(),
                'category' => Type::string(),
                'brand' => Type::string(),
                'gallery' => Type::listOf(Type::string()),
                'prices' => Type::listOf(new ObjectType([
                    'name' => 'Price',
                    'fields' => [
                        'amount' => Type::float(),
                        'currency' => new ObjectType([
                            'name' => 'Currency',
                            'fields' => [
                                'label' => Type::string(),
                                'symbol' => Type::string()
                            ]
                        ])
                    ]
                ])),
                'attributes' => Type::listOf(new ObjectType([
                    'name' => 'Attribute',
                    'fields' => [
                        'id' => Type::id(),
                        'name' => Type::string(),
                        'type' => Type::string(),
                        'items' => Type::listOf(new ObjectType([
                            'name' => 'AttributeItem',
                            'fields' => [
                                'id' => Type::id(),
                                'value' => Type::string(),
                                'displayValue' => Type::string(),
                            ]
                        ]))
                    ]
                ]))
            ]
        ]);

        return self::$product;
    }

    public static function category(): ObjectType
    {
        if (self::$category) return self::$category;

        self::$category = new ObjectType([
            'name' => 'Category',
            'fields' => [
                'id' => Type::nonNull(Type::id()),
                'name' => Type::nonNull(Type::string()),
            ]
        ]);

        return self::$category;
    }
}
