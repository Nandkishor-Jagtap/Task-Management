<?php
declare(strict_types=1);

use App\Router;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testMatchExactPath(): void
    {
        $router = new Router();
        $router->get('/health', [Dummy::class, 'method']);

        $matched = $router->match('GET', '/health');
        $this->assertNotNull($matched);
        $this->assertSame('/health', $matched->path);
    }

    public function testNoMatchReturnsNull(): void
    {
        $router = new Router();
        $matched = $router->match('GET', '/missing');
        $this->assertNull($matched);
    }
    
    public function testPostRouteMatch(): void
    {
        $router = new Router();
        $router->post('/tasks/create', [Dummy::class, 'method']);
        
        $matched = $router->match('POST', '/tasks/create');
        $this->assertNotNull($matched);
        $this->assertSame('POST', $matched->method);
    }
    
    public function testMultipleRoutes(): void
    {
        $router = new Router();
        $router->get('/home', [Dummy::class, 'method']);
        $router->get('/tasks', [Dummy::class, 'method']);
        $router->post('/tasks/create', [Dummy::class, 'method']);
        
        $homeRoute = $router->match('GET', '/home');
        $tasksRoute = $router->match('GET', '/tasks');
        $createRoute = $router->match('POST', '/tasks/create');
        
        $this->assertNotNull($homeRoute);
        $this->assertNotNull($tasksRoute);
        $this->assertNotNull($createRoute);
        
        $this->assertSame('/home', $homeRoute->path);
        $this->assertSame('/tasks', $tasksRoute->path);
        $this->assertSame('/tasks/create', $createRoute->path);
    }
    
    public function testCaseInsensitiveMethod(): void
    {
        $router = new Router();
        $router->get('/test', [Dummy::class, 'method']);
        
        $matched = $router->match('get', '/test');
        $this->assertNotNull($matched);
    }
}

final class Dummy
{
    public function method(): void
    {
    }
}
