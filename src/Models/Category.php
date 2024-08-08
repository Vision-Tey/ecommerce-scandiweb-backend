<?php

namespace App\Models;

use PDO;
use PDOException;
use Exception;

class Category extends AbstractModel
{
    public static function getAll()
    {
        $query = 'select * from categories';
        return static::queryAllProducts($query);
    }
}
