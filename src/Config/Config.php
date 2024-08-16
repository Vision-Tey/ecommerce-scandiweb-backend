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
        $host = getenv('MYSQL_HOST') ?: '102.134.147.233';
        $dbname = getenv('MYSQL_DATABASE') ?: 'gsrlqftebzstmsgwkksduajx';
        $user = getenv('MYSQL_USER') ?: 'vnvpyauobgavclxa';
        $password = getenv('MYSQL_PASSWORD') ?: 'secret1234';
        $port = getenv('PORT') ?: '32764';

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
