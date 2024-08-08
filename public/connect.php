<?php

$dsn = 'mysql:host=db;dbname=your_database;charset=utf8';
$username = 'root';
$password = 'secret';

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_log("Database connected successfully."); // Log message for successful connection

    echo "Connection successful!<br>";

    // Query to fetch products
    $query = "SELECT * FROM products";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    // Fetch and display products
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($products) {
        var_dump($products);
    } else {
        echo "No products found.";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
