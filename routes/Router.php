<?php

declare(strict_types=1);

namespace Routes;

class Router
{
    private array $routes = [];

    public function add(string $route, callable $callback): void
    {
        $this->routes[$route] = $callback;
    }

    public function dispatch(string $requestedUri): void
    {
        if (array_key_exists($requestedUri, $this->routes)) {
            call_user_func($this->routes[$requestedUri]);
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
    }
}