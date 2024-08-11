<?php

namespace App\Schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderProductType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'OrderProduct',
            'fields' => [
                'id' => Type::nonNull(Type::int()),
                'productId' => Type::string(),
                'productName' => Type::string(),
                'quantity' => Type::int(),
                'price' => Type::string(),
                'attributes' => Type::string()
            ]
        ]);
    }
}
