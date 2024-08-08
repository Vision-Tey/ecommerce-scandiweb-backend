<?php

declare(strict_types=1);

namespace src\Utils;

use src\Types\Scalars\JsonType;

final class TypeRegistry
{
 private static array $types = [];

 public static function getType(string $className): \Closure
 {
  return fn () => self::$types[$className] ??= new $className();
 }

 public static function getJsonType()
 {
  return self::getType(JsonType::class);
 }
}