<?php
declare(strict_types=1);

namespace App;

final class Router
{
    /**
     * @var array<string, Route[]>
     */
    private array $routes = [
        'GET' => [],
        'POST' => [],
        'PUT' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    public function get(string $path, callable|array $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function put(string $path, callable|array $handler): void
    {
        $this->add('PUT', $path, $handler);
    }

    public function patch(string $path, callable|array $handler): void
    {
        $this->add('PATCH', $path, $handler);
    }

    public function delete(string $path, callable|array $handler): void
    {
        $this->add('DELETE', $path, $handler);
    }

    public function add(string $method, string $path, callable|array $handler): void
    {
        $this->routes[$method][] = new Route($method, $path, $handler);
    }

    public function match(string $method, string $path): ?Route
    {
        $method = strtoupper($method);
        $candidates = $this->routes[$method] ?? [];

        foreach ($candidates as $route) {
            if ($route->path === $path) {
                return $route;
            }
        }

        return null;
    }
}

/**
 * Simple immutable route definition.
 */
final class Route
{
    public function __construct(
        public string $method,
        public string $path,
        public array $handler
    ) {
    }
}
