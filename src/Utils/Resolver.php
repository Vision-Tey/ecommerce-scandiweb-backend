<?php


namespace App\Utils;

use Throwable;

final class Resolver
{
 public static function resolver(callable $resolveFunc, array $arg = [], bool $isArgArray = false)
 {
  try {
    var_dump("utils");
   $args[0] = $arg[0] ?? 0;
   $args[1] = $isArgArray;
   return $isArgArray ? call_user_func_array($resolveFunc, $args)  : call_user_func($resolveFunc, $arg[0] ?? 0);
  } catch (Throwable $e) {
    $output = [
        'error' => [
            'message' => $e->getMessage(),
        ],
    ];
    echo json_encode($output);
   echo "Error thrown";
  }
 }
}