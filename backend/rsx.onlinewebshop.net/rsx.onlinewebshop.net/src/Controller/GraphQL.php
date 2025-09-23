<?php

namespace App\Controller;

use GraphQL\GraphQL as GraphQLBase;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;
use GraphQL\Type\SchemaConfig;
use RuntimeException;
use Throwable;
use App\Repositories\ProductRepository;
use App\GraphQL\Types\TypeRegistry;
use App\Mutations\OrderMutation;
use App\Repositories\CategoryRepository;



class GraphQL {
    static public function handle() {
        try {

        $queryType = new ObjectType([
            'name' => 'Query',
            'fields' => [
                'products' => [
                    'type' => Type::listOf(TypeRegistry::product()),
                    'resolve' => function () {
                        $repo = new ProductRepository();
                        return $repo->getAll();
                    }
                ],
                'categories' => [
                    'type' => Type::listOf(TypeRegistry::category()),
                    'resolve' => function () {
                        $repo = new CategoryRepository();
                        return $repo->getAll(); // This now dynamically fetches from DB
                    }
                ]
            ]
        ]);

        
           $mutationType = new ObjectType([
            'name' => 'Mutation',
            'fields' => [
                'placeOrder' => [
                    'type' => Type::string(), // You can replace this later with a more detailed return type
                    'args' => [
                        'items' => Type::nonNull(Type::listOf(TypeRegistry::orderInput()))
                    ],
                    'resolve' => function ($root, $args) {
                        return OrderMutation::placeOrder($args['items']);
                    }
                ]
            ],
        ]);
        
          
            $schema = new Schema(
                (new SchemaConfig())
                ->setQuery($queryType)
                ->setMutation($mutationType)
            );
        
            $rawInput = file_get_contents('php://input');
            if ($rawInput === false) {
                throw new RuntimeException('Failed to get php://input');
            }
        
            $input = json_decode($rawInput, true);
            $query = $input['query'];
            $variableValues = $input['variables'] ?? null;
        
            $rootValue = ['prefix' => 'You said: '];
            $result = GraphQLBase::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output = $result->toArray();
        } catch (Throwable $e) {
            $output = [
                'error' => [
                    'message' => $e->getMessage(),
                ],
            ];
        }

        header('Content-Type: application/json; charset=UTF-8');
        return json_encode($output);
    }
}