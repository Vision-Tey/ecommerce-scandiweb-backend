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
        $host = getenv('MYSQL_HOST') ?: 'wm63be5w8m7gs25a.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
        $dbname = getenv('MYSQL_DATABASE') ?: 'dgy0oetsdffpvqr6';
        $user = getenv('MYSQL_USER') ?: 'lylxpkd89i9fa75b';
        $password = getenv('MYSQL_PASSWORD') ?: 'v6yphu5lfxddtqf2';
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
