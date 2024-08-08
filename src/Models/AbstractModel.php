<?php

namespace App\Models;

use App\Database\Database;

abstract class AbstractModel extends Database
{
    abstract public static function getAll();
}
