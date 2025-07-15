<?php

namespace Core;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get($uri, $action)
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action)
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch($uri, $method)
    {
        $action = $this->routes[$method][$uri] ?? null;

        if (!$action) {
            http_response_code(404);
            echo "404 - Ruta no encontrada";
            exit;
        }

        [$controller, $method] = $action;

        $controllerInstance = new $controller();
        return $controllerInstance->$method();
    }
}
