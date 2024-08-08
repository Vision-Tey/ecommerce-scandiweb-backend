<?php

// require_once __DIR__ . '/config/config.php';

// $config = include __DIR__ . '/config/config.php';
$dsn = 'mysql:host=db;dbname=your_database;charset=utf8';
$username = 'root';
$password = 'secret';
try {

    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    error_log("Database connected successfully."); // Log message for successful connection


    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
