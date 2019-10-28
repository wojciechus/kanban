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
    public static function env(string $name)
    {
        $value = getenv($name);

        if (!empty($value)) {
            return $value;
        }

        $default = ConfigDefault::getDefault($name);

        if (!empty($default)) {
            return $default;
        }

        die (sprintf('Environment variable %s not found or has no value', $name));
    }
}