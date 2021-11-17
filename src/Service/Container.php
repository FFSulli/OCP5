<?php

namespace App\Service;

class Container
{
    private array $recipes = [];

    private array $instances = [];

    private static ?Container $instance = null;

    /**
     * Make constructor private for Singleton
     */
    private function __construct()
    {
    }

    /**
     * Access to static prop
     */
    public static function instance(): self
    {
        if (null === self::$instance) {
            self::$instance = new self();
            require __DIR__ . "/../../config/container.php";
        }

        return self::$instance;
    }

    public function registerService(string $serviceName, \Closure $recipe): self
    {
        $this->recipes[$serviceName] = $recipe;
        return $this;
    }

    public function get(string $serviceName)
    {
        // If an instance exists, return it
        if (array_key_exists($serviceName, $this->instances)) {
            return $this->instances[$serviceName];
        }

        // If no recipes, return null
        if (! array_key_exists($serviceName, $this->recipes)) {
            return null;
        }

        // Get recipe closure
        $recipe = $this->recipes[$serviceName];

        // Execute, store instance and return it
        $instance = $recipe($this);
        $this->instances[$serviceName] = $instance;
        return $instance;
    }

    public function set(string $serviceName, $value): self
    {
        $this->instances[$serviceName] = $value;
        return $this;
    }
}
