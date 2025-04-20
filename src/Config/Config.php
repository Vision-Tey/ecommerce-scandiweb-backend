<?php

namespace App\Config;

use Dotenv\Dotenv;

final class Config
{
    public static function getConfig(): array
    {
        if (!isset($_ENV['MYSQL_HOST'])) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $dotenv->load();
        }

        return [
            'db' => [
                'host' => $_ENV['MYSQL_HOST'],
                'dbname' => $_ENV['MYSQL_DATABASE'],
                'user' => $_ENV['MYSQL_USER'],
                'password' => $_ENV['MYSQL_PASSWORD'],
                'port' => $_ENV['PORT']
            ]
        ];
    }
}

Config::getConfig();
