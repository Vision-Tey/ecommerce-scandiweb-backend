<?php

namespace App\Schema;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class OrderType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Order',
            'fields' => [
                'id' => Type::nonNull(Type::int()),
                'customerName' => Type::nonNull(Type::string()),
                'customerEmail' => Type::nonNull(Type::string()),
                'customerAddress' => Type::nonNull(Type::string()),
                'status' => Type::nonNull(Type::string()),
                'totalPrice' => Type::nonNull(Type::float()),
                'products' => Type::listOf(new OrderProductType())
            ]
        ]);
    }
}
