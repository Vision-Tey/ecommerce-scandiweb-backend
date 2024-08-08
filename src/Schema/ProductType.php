<?php

namespace App\Schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class ProductType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Product',
            'fields' => [
                'id' => Type::nonNull(Type::string()),
                'name' => Type::nonNull(Type::string()),
                'inStock' => Type::nonNull(Type::boolean()),
                'description' => Type::nonNull(Type::string()),
                'category' => Type::nonNull(Type::string()),
                'brand' => Type::nonNull(Type::string()),
                'gallery' => Type::listOf(Type::nonNull(Type::string())),
                'attributes' => Type::listOf(Type::nonNull(new ObjectType([
                    'name' => 'Attribute',
                    'fields' => [
                        'name' => Type::nonNull(Type::string()),
                        'items' => Type::listOf(Type::nonNull(new ObjectType([
                            'name' => 'AttributeItem',
                            'fields' => [
                                'displayValue' => Type::nonNull(Type::string()),
                                'value' => Type::nonNull(Type::string()),
                                'id' => Type::nonNull(Type::string())
                            ]
                        ])))
                    ]
                ]))),
                'prices' => Type::listOf(Type::nonNull(new ObjectType([
                    'name' => 'Price',
                    'fields' => [
                        'amount' => Type::nonNull(Type::float()),
                        'currency' => Type::nonNull(new ObjectType([
                            'name' => 'Currency',
                            'fields' => [
                                'label' => Type::nonNull(Type::string()),
                                'symbol' => Type::nonNull(Type::string())
                            ]
                        ]))
                    ]
                ])))
            ]
        ]);
    }
}
