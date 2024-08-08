<?php

namespace App\Schema;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Error\Error;
use Exception;

class QueryType extends ObjectType
{
    public function __construct()
    {
        parent::__construct([
            'name' => 'Query',
            'fields' => [
                'categories' => [
                    'type' => Type::listOf(new CategoryType()),
                    'description' => 'Categories',
                    'resolve' => function () {
                        try {
                            return Category::getAll();
                        } catch (Exception $e) {
                            error_log('Error fetching categories: ' . $e->getMessage());
                            throw new Error('Error fetching categories: ' . $e->getMessage());
                        }
                    }
                ],
                'products' => [
                    'type' => Type::listOf(new ProductType()),
                    'description' => 'All products',
                    'resolve' => function () {
                        try {
                            $products = Product::getAll();
                            foreach ($products as &$product) {
                                // Map in_stock to inStock
                                $product['inStock'] = (bool) $product['in_stock'];
                                unset($product['in_stock']);

                                // Decode JSON fields if they are not null
                                $product['gallery'] = $this->decodeJson($product['gallery']);
                                $product['attributes'] = $this->decodeJson($product['attributes']);
                                $product['prices'] = $this->decodeJson($product['prices']);
                            }
                            return $products;
                        } catch (Exception $e) {
                            error_log('Error fetching products: ' . $e->getMessage());
                            throw new Error('Error fetching products: ' . $e->getMessage());
                        }
                    }
                ],
                'orders' => [
                    'type' => Type::listOf(new OrderType()),
                    'description' => 'All orders with their products',
                    'resolve' => function () {
                        try {
                            $orders = Order::getAllOrders();
                            foreach ($orders as &$order) {
                                // Map database fields to GraphQL fields
                                $order['id'] = $order['order_id'];
                                $order['customerName'] = $order['customer_name'];
                                $order['customerEmail'] = $order['customer_email'];
                                $order['customerAddress'] = $order['customer_address'];
                                $order['totalPrice'] = $order['total_price'];

                                // Map product fields inside orders
                                foreach ($order['products'] as &$product) {
                                    $product['productId'] = $product['product_id'];
                                    $product['productName'] = $product['product_name'];
                                    $product['quantity'] = $product['quantity'];
                                    $product['price'] = $product['total_price'];
                                    $product['attributes'] = $this->decodeJson($product['attributes']);

                                    // Unset the original database fields
                                    unset($product['product_id']);
                                    unset($product['product_name']);
                                    unset($product['total_price']);
                                }

                                // Unset the original database fields
                                unset($order['order_id']);
                                unset($order['customer_name']);
                                unset($order['customer_email']);
                                unset($order['customer_address']);
                                unset($order['total_price']);
                            }
                            return $orders;
                        } catch (Exception $e) {
                            error_log('Error fetching orders: ' . $e->getMessage());
                            throw new Error('Error fetching orders: ' . $e->getMessage());
                        }
                    }
                ],
            ]
        ]);
    }

    private function decodeJson($json)
    {
        return $json ? json_decode($json, true) : [];
    }
}
