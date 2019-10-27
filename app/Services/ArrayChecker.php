<?php

namespace App\Services;

class ArrayChecker
{
    public static function hasValue(?array $array, $key): bool
    {
        return is_array($array) && array_key_exists($key, $array) && !empty($array[$key]);
    }
}