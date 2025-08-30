<?php

namespace App\GraphQL\Inputs;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;

class OrderInputType extends InputObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderInput',
            'fields' => [
                'id' => Type::nonNull(Type::id()),
                'name' => Type::string(),
                'quantity' => Type::nonNull(Type::int()),
                'price' => Type::nonNull(new InputObjectType([
                    'name' => 'PriceInput',
                    'fields' => [
                        'amount' => Type::nonNull(Type::float()),
                        'currency' => new InputObjectType([
                            'name' => 'CurrencyInput',
                            'fields' => [
                                'label' => Type::string(),
                                'symbol' => Type::string()
                            ]
                        ])
                    ]
                ])),
                'selectedAttributes' => Type::listOf(new InputObjectType([
                    'name' => 'SelectedAttributeInput',
                    'fields' => [
                        'name' => Type::string(),
                        'value' => Type::string()
                    ]
                ]))
            ],
        ]);
    }
}
