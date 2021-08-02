<?php


namespace App\Service\DotEnv;


class DotEnvService
{

    public function __construct()
    {

        (new DotEnv(__DIR__ . '/../../../.env'))->load();

    }

    public function get(string $key)
    {

        return getenv($key);

    }
}
