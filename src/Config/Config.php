<?php
namespace App\Config;

final class Config
{
 public static function getConfig(): array
 {
    return [
        'db' => [
            'host' => getenv('MYSQL_HOST'),
            'dbname' => getenv('MYSQL_DATABASE'),
            'user' => getenv('MYSQL_USER'),
            'password' => getenv('MYSQL_PASSWORD'),
        ]
    ];
 }
}

Config::getConfig();


