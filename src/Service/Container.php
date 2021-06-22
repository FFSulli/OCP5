<?php

namespace App\Service;

class Container
{
    private array $recipes = [];

    private array $instances = [];

    public function registerService(string $serviceName, \Closure $recipe): self
    {
        $this->recipes[$serviceName] = $recipe;
        return $this;
    }

    public function get(string $serviceName): ?string
    {
        if (array_key_exists($serviceName, $this->instances)) {
            return $this->instances[$serviceName];
        }

        if (! array_key_exists($serviceName, $this->recipes)) {
            return null;
        }

        $recipe = $this->recipes[$serviceName];

        $instance = $recipe();
        $this->instances[$serviceName] = $instance;
        return $instance;
    }
}
