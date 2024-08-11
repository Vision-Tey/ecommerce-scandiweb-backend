<?php

namespace App\Models;

use App\Database\Database;
use Exception;
use PDO;

class Order
{
    public static function createOrder($orderData)
    {
        $pdo = Database::getInstance()->getConnection();

        try {
            $pdo->beginTransaction();

            // Insert the order
            $query = "INSERT INTO orders (customer_name, customer_email, customer_address, status, total_price)
                      VALUES (:customer_name, :customer_email, :customer_address, :status, :total_price)";
            $stmt = $pdo->prepare($query);
            $stmt->execute([
                ':customer_name' => $orderData['customer_name'],
                ':customer_email' => $orderData['customer_email'],
                ':customer_address' => $orderData['customer_address'],
                ':status' => $orderData['status'],
                ':total_price' => $orderData['total_price']
            ]);
            $orderId = $pdo->lastInsertId();

            if (!$orderId) {
                throw new Exception("Failed to retrieve the last inserted order ID.");
            }

            // Insert order products
            $query = "INSERT INTO order_products (order_id, product_id, quantity, total_price, attributes)
                      VALUES (:order_id, :product_id, :quantity, :total_price, :attributes)";
            $stmt = $pdo->prepare($query);
            foreach ($orderData['products'] as $product) {
                $stmt->execute([
                    ':order_id' => $orderId,
                    ':product_id' => $product['product_id'],
                    ':quantity' => $product['quantity'],
                    ':total_price' => $product['total_price'],
                    ':attributes' => json_encode($product['attributes'])
                ]);
            }

            $pdo->commit();
            return $orderId;
        } catch (Exception $e) {
            $pdo->rollBack();
            error_log("Error inserting order: " . $e->getMessage());
            throw new Exception("Error inserting order: " . $e->getMessage());
        }
    }

    public static function getOrderById($orderId)
    {
        $pdo = Database::getInstance()->getConnection();

        try {
            // Fetch the order
            $query = "SELECT * FROM orders WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id' => $orderId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                throw new Exception("Order not found");
            }

            // Fetch the order products
            $query = "SELECT * FROM order_products WHERE order_id = :order_id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':order_id' => $orderId]);
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $order['products'] = $products;

            return $order;
        } catch (Exception $e) {
            error_log("Error fetching order: " . $e->getMessage());
            throw new Exception("Error fetching order: " . $e->getMessage());
        }
    }

    public static function getAllOrders()
    {
        $pdo = Database::getInstance()->getConnection();

        try {
            // Fetch all orders with products
            $query = "
                SELECT 
                    o.id AS order_id,
                    o.customer_name,
                    o.customer_email,
                    o.customer_address,
                    o.status,
                    o.total_price,
                    (SELECT JSON_ARRAYAGG(
                        JSON_OBJECT(
                            'product_id', op.product_id,
                            'product_name', p.name,
                            'quantity', op.quantity,
                            'total_price', op.total_price,
                            'attributes', op.attributes
                        )
                    ) 
                    FROM order_products op 
                    JOIN products p ON op.product_id = p.id 
                    WHERE op.order_id = o.id) AS products
                FROM 
                    orders o;
            ";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Decode JSON products field if it is not null
            foreach ($orders as &$order) {
                if (!is_null($order['products'])) {
                    $order['products'] = json_decode($order['products'], true);
                } else {
                    $order['products'] = [];
                }
            }

            return $orders;
        } catch (Exception $e) {
            error_log('Error fetching orders: ' . $e->getMessage());
            throw new Exception("Error fetching orders: " . $e->getMessage());
        }
    }
}
