<?php
declare(strict_types=1);

namespace App\Service;


class Route
{
    const HTTP_METHODS = ['GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
    private string $path;
    private ?array $methods;

    public function __construct(string $path, ?array $methods)
    {

        $this->path = $path;
        $this->methods = $methods;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     * @return Route
     */
    public function setPath(string $path): Route
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
}
