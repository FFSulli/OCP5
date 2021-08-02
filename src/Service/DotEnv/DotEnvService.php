<?php


namespace App\Service\DotEnv;


class DotEnvService
{

    public function __construct()
    {

        (new DotEnv(__DIR__ . '/../../../.env'))->load();

    }

    public function get(string $key): string
    {

        $envKey = getenv($key);
        if ($envKey === false) {
            var_dump('Test');
        }

        return $envKey;

    }
}
