<?php

namespace App\GraphQL;

class SimpleGraphQL
{
    private array $schema = [];
    private array $resolvers = [];

    public function __construct()
    {
        $this->defineSchema();
        $this->defineResolvers();
    }

    private function defineSchema(): void
    {
        $this->schema = [
            'Query' => [
                'products' => [
                    'type' => '[Product]',
                    'resolve' => 'resolveProducts'
                ],
                'product' => [
                    'type' => 'Product',
                    'args' => ['id' => 'ID!'],
                    'resolve' => 'resolveProduct'
                ],
                'categories' => [
                    'type' => '[Category]',
                    'resolve' => 'resolveCategories'
                ]
            ],
            'Mutation' => [
                'placeOrder' => [
                    'type' => 'String',
                    'args' => ['items' => '[OrderInputType]!'],
                    'resolve' => 'resolvePlaceOrder'
                ]
            ],
            'Types' => [
                'Product' => [
                    'id' => 'ID!',
                    'name' => 'String!',
                    'inStock' => 'Boolean!',
                    'description' => 'String',
                    'category' => 'String!',
                    'brand' => 'String',
                    'gallery' => '[String]',
                    'prices' => '[Price]!',
                    'attributes' => '[Attribute]'
                ],
                'Price' => [
                    'amount' => 'Float!',
                    'currency' => 'Currency!'
                ],
                'Currency' => [
                    'label' => 'String!',
                    'symbol' => 'String!'
                ],
                'Attribute' => [
                    'id' => 'String!',
                    'name' => 'String!',
                    'type' => 'String!',
                    'items' => '[AttributeItem]'
                ],
                'AttributeItem' => [
                    'id' => 'String!',
                    'value' => 'String!',
                    'displayValue' => 'String!'
                ],
                'Category' => [
                    'id' => 'String',
                    'name' => 'String!'
                ],
                'OrderInputType' => [
                    'product_id' => 'String!',
                    'quantity' => 'Int!',
                    'selected_attributes' => 'String'
                ]
            ]
        ];
    }

    private function defineResolvers(): void
    {
        $this->resolvers = [
            'resolveProducts' => function ($root, $args, $context) {
                return $context['productRepository']->getAll();
            },
            'resolveProduct' => function ($root, $args, $context) {
                return $context['productRepository']->getById($args['id']);
            },
            'resolveCategories' => function ($root, $args, $context) {
                return $context['categoryRepository']->getAll();
            },
            'resolvePlaceOrder' => function ($root, $args, $context) {
                try {
                    $success = $context['orderRepository']->createOrder($args['items']);
                    return $success ? "Order placed successfully" : "Order failed";
                } catch (\Exception $e) {
                    return "Order failed: " . $e->getMessage();
                }
            }
        ];
    }

    public function execute(string $query, array $variables = [], array $context = []): array
    {
        try {
            // Simple query parsing
            $operation = $this->parseOperation($query);
            $fields = $this->parseFields($query);
            $fieldArgs = $this->parseFieldArguments($query, $variables);

            if ($operation === 'query') {
                return $this->executeQuery($fields, $fieldArgs, $context);
            } elseif ($operation === 'mutation') {
                return $this->executeMutation($fields, $fieldArgs, $context);
            }

            throw new \Exception('Invalid operation');

        } catch (\Exception $e) {
            return [
                'errors' => [
                    ['message' => $e->getMessage()]
                ]
            ];
        }
    }

    private function parseOperation(string $query): string
    {
        if (preg_match('/^\s*mutation/i', $query)) {
            return 'mutation';
        }
        return 'query';
    }

    private function parseFields(string $query): array
    {
        $fields = [];

        // Extract field names - improved regex for nested queries
        if (preg_match_all('/(?:query|mutation)?\s*(?:\w+\s*)?\{\s*(\w+)/', $query, $matches)) {
            foreach ($matches[1] as $field) {
                if (!in_array($field, ['query', 'mutation', 'Query', 'Mutation'])) {
                    $fields[] = $field;
                }
            }
        }

        // Also handle direct field queries
        if (preg_match_all('/(\w+)(?:\s*\([^)]*\))?\s*\{/', $query, $matches)) {
            foreach ($matches[1] as $field) {
                if (!in_array($field, ['query', 'mutation', 'Query', 'Mutation']) && !in_array($field, $fields)) {
                    $fields[] = $field;
                }
            }
        }

        return $fields;
    }

    private function parseFieldArguments(string $query, array $variables): array
    {
        $fieldArgs = [];

        // Parse arguments from query
        if (preg_match_all('/(\w+)\s*\(([^)]+)\)/', $query, $matches)) {
            for ($i = 0; $i < count($matches[1]); $i++) {
                $fieldName = $matches[1][$i];
                $argsString = $matches[2][$i];

                $args = [];

                // Parse variable references like $id
                if (preg_match_all('/(\w+):\s*\$(\w+)/', $argsString, $varMatches)) {
                    for ($j = 0; $j < count($varMatches[1]); $j++) {
                        $argName = $varMatches[1][$j];
                        $varName = $varMatches[2][$j];
                        if (isset($variables[$varName])) {
                            $args[$argName] = $variables[$varName];
                        }
                    }
                }

                // Parse direct values like id: "some-id"
                if (preg_match_all('/(\w+):\s*"([^"]+)"/', $argsString, $directMatches)) {
                    for ($j = 0; $j < count($directMatches[1]); $j++) {
                        $argName = $directMatches[1][$j];
                        $argValue = $directMatches[2][$j];
                        $args[$argName] = $argValue;
                    }
                }

                $fieldArgs[$fieldName] = $args;
            }
        }

        return $fieldArgs;
    }

    private function executeQuery(array $fields, array $fieldArgs, array $context): array
    {
        $data = [];

        foreach ($fields as $field) {
            if (isset($this->schema['Query'][$field])) {
                $resolver = $this->schema['Query'][$field]['resolve'];
                if (isset($this->resolvers[$resolver])) {
                    $args = $fieldArgs[$field] ?? [];
                    $data[$field] = $this->resolvers[$resolver](null, $args, $context);
                }
            }
        }

        return ['data' => $data];
    }

    private function executeMutation(array $fields, array $fieldArgs, array $context): array
    {
        $data = [];

        foreach ($fields as $field) {
            if (isset($this->schema['Mutation'][$field])) {
                $resolver = $this->schema['Mutation'][$field]['resolve'];
                if (isset($this->resolvers[$resolver])) {
                    $args = $fieldArgs[$field] ?? [];
                    $data[$field] = $this->resolvers[$resolver](null, $args, $context);
                }
            }
        }

        return ['data' => $data];
    }

    public function getSchema(): array
    {
        return $this->schema;
    }

    public function introspect(): array
    {
        // Basic introspection for schema exploration
        return [
            'data' => [
                '__schema' => [
                    'queryType' => ['name' => 'Query'],
                    'mutationType' => ['name' => 'Mutation'],
                    'types' => array_keys($this->schema['Types'] ?? [])
                ]
            ]
        ];
    }
}
