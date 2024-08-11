<?php

namespace App\Config;

final class Config
{
    public static function getConfig(): array
    {
        $host = getenv('MYSQL_HOST');
        $dbname = getenv('MYSQL_DATABASE');
        $user = getenv('MYSQL_USER');
        $password = getenv('MYSQL_PASSWORD');
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
