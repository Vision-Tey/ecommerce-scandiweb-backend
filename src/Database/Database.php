<?php

namespace App\Database;

use App\Config\Config;
use Exception;
use PDO;
use PDOStatement;

class Database
{
    protected PDO $pdo;  // PDO instance for database connection
    protected PDOStatement $statement;  // PDO statement for prepared queries
    private static ?self $instance = null;  // Singleton instance of Database


    public function __construct($config, $user, $pass)
    {
        $dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['dbname'] . ';port=' . $config['db']['port'] .';charset=utf8';
        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    private static function checkInstance()
    {
        if (self::$instance === null) {
            $config = Config::getConfig();
            self::$instance = new self($config, $config['db']['user'], $config['db']['password']);
        }
    }

    public static function getInstance()
    {
        self::checkInstance();
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->pdo;
    }

    public static function queryAllProducts($query, $params = [])
    {
        try {
            self::checkInstance();
            return self::$instance->query($query, $params)->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Database error', 'message' => $e->getMessage()]);
            return null;
        }
    }

    private function query($query, $params = []): PDOStatement
    {
        $this->statement = $this->pdo->prepare($query);
        if (!is_array($params)) {
            $params = []; 
        }
        $this->statement->execute($params);
        return $this->statement;
    }
}
