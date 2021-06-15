<?php
declare(strict_types=1);

namespace App\Service;


use App\Service\Http\Request;
use App\Service\Http\Response;

class NewRouter
{
    private array $routes = [];

    public function __construct(array $rawRoutes)
    {
        $this->routes;
        $this->loadRoutes($rawRoutes);
    }

    public function run(Request $request): Response
    {
        foreach ($this->routes as $route) {
            if ($route['path'] === $request && in_array($request->getMethod(), $this->routes['methods'])) {

            }
        }

            // Si la route est celle demandée : On redirige vers le controller concerné

            // Si la route n'est pas celle demandée : On passe à la suivante

            // Si aucune route ne correspond, renvoyer une 404

    }

    private function loadRoutes(array $rawRoutes)
    {
        foreach ($rawRoutes as $route) {
            $route = new Route($route['path'], $route['methods']);
            $this->routes[] = $route;
        }
    }
}
