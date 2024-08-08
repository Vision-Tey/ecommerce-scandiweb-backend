<?php

namespace App\Schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class AttributeType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Attribute',
            'fields' => [
                'id' => ['type' => Type::string()],
                'name' => ['type' => Type::string()],
                'type' => ['type' => Type::string()],
                'items' => [
                    'type' => Type::listOf(new ObjectType([
                        'name' => 'AttributeItem',
                        'fields' => [
                            'displayValue' => ['type' => Type::string()],
                            'value' => ['type' => Type::string()],
                            'id' => ['type' => Type::string()],
                        ],
                    ])),
                ],
            ],
        ]);
    }
}
