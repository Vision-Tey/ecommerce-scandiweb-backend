<?php

namespace App\Schema;

use App\Models\Order;
use Exception;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Error\Error;

class MutationType extends ObjectType
{
    public function __construct()
    {
        $orderProductInput = new InputObjectType([
            'name' => 'OrderProductInput',
            'fields' => [
                'product_id' => Type::nonNull(Type::string()),
                'quantity' => Type::nonNull(Type::int()),
                'total_price' => Type::nonNull(Type::float()),
                'attributes' => Type::nonNull(Type::string()) // Adjust type based on your actual attributes structure
            ]
        ]);

        $config = [
            'name' => 'Mutation',
            'fields' => [
                'createOrder' => [
                    'type' => Type::nonNull(Type::int()), // Return the ID of the new order
                    'description' => 'Create a new order',
                    'args' => [
                        'customer_name' => Type::nonNull(Type::string()),
                        'customer_email' => Type::nonNull(Type::string()),
                        'customer_address' => Type::nonNull(Type::string()),
                        'status' => Type::nonNull(Type::string()),
                        'total_price' => Type::nonNull(Type::float()),
                        'products' => Type::nonNull(Type::listOf(Type::nonNull($orderProductInput)))
                    ],
                    'resolve' => function ($root, $args) {
                        try {
                            $orderId = Order::createOrder($args);
                            return $orderId;
                        } catch (Exception $e) {
                            throw new Error('Error creating order: ' . $e->getMessage());
                        }
                    }
                ]
            ]
        ];

        parent::__construct($config);
    }
}
