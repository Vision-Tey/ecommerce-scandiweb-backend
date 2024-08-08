<?php

namespace App\Schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class CategoryType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Category',
            'fields' => [
                'name' => Type::nonNull(Type::string()),
                '__typename' => Type::nonNull(Type::string()),
            ]
        ]);
    }
}
