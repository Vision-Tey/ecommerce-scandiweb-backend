<?php

namespace App\Config;

use Dotenv\Dotenv;

final class Config
{
    public static function getConfig(): array
    {
        // Load environment variables from .env file
        $dotenv = Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();

        // Fetch environment variables with provided defaults
        $host = getenv('MYSQL_HOST') ?: 's9xpbd61ok2i7drv.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
        $dbname = getenv('MYSQL_DATABASE') ?: 'a5qbyz129p2cinto';
        $user = getenv('MYSQL_USER') ?: 'hvwpm5dlrcqr34bb';
        $password = getenv('MYSQL_PASSWORD') ?: 'iplss20sb1qikjfn';
        $port = getenv('PORT') ?: '3306';

        return [
            'db' => [
                'host' => $host,
                'dbname' => $dbname,
                'user' => $user,
                'password' => $password,
                'port' => $port
            ]
        ];
    }
}

Config::getConfig();
