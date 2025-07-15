<?php

if (!function_exists('view')) {
    function view(string $path, array $data = [])
    {
        extract($data);
        $viewPath = __DIR__ . '/../app/Views/' . str_replace('.', '/', $path) . '.php';

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "Vista no encontrada: $path";
        }
    }
}

if (!function_exists('route')) {
    function route(string $uri): string
    {
        return $uri;
    }
}
