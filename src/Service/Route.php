<?php

declare(strict_types=1);

namespace App\Service;

class Route
{
    const HTTP_METHODS = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
    private $path;
    private ?array $methods;
    private $handler;

    public function __construct(callable $path, ?array $methods, callable $handler)
    {

        $this->path = $path;
        $this->methods = $methods;
        $this->handler = $handler;
    }

    /**
     * @return callable
     */
    public function getPath(): callable
    {
        return $this->path;
    }

    /**
     * @param callable $path
     * @return Route
     */
    public function setPath(callable $path): Route
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * @param array|null $methods
     * @return Route
     */
    public function setMethods(?array $methods): Route
    {
        if (null === $methods) {
            $methods = self::HTTP_METHODS;
        }

        $this->methods = $methods;
        return $this;
    }

    /**
     * @return callable
     */
    public function getHandler(): callable
    {
        return $this->handler;
    }

    /**
     * @param callable $handler
     * @return Route
     */
    public function setHandler(callable $handler): Route
    {
        $this->handler = $handler;
        return $this;
    }

    public function match(string $path): bool
    {
        $pathHandler = $this->path;

        return $pathHandler($path);
    }
}
