<?php

namespace App\Environment;

class EnvironmentResolver
{
    private function __construct()
    {
    }

    /**
     * @return mixed
     */
    public static function env(string $name, $default = NULL)
    {
        $value = getenv($name);

        if (!empty($value)) {
            return $value;
        }
        if (!empty($defalut)) {
            return $default;
        }

        die (sprintf('Environment variable %s not found or has no value', $name));
    }
}