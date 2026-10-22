<?php

namespace App\Helpers;

class Container
{

private static array $container = [];

public static function set(string $key, $val):void
{
    self::$container[$key] = $val;
}

public static function get(string $key, $default = '')
{
    return self::$container[$key] ?? $default;
}

}