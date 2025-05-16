<?php
namespace Core;

class Router
{
    protected $routes = [];

    public function add($method, $uri, $action)
    {
        $this->routes[] = compact('method', 'uri', 'action');
    }

    public function get($uri, $action)
    {
        $this->add('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->add('POST', $uri, $action);
    }

    public function dispatch($uri, $method)
    {
        $path = parse_url($uri, PHP_URL_PATH);
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $path) {
                return $this->invokeAction($route['action']);
            }
        }
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
        exit;
    }

    protected function invokeAction($action)
    {
        if (is_callable($action)) {
            return call_user_func($action);
        }
        list($controller, $method) = explode('@', $action);
        $controller = "App\Controllers\{$controller}";
        if (!class_exists($controller)) {
            throw new \Exception("Controller {$controller} not found");
        }
        $instance = new $controller;
        if (!method_exists($instance, $method)) {
            throw new \Exception("Method {$method} not found in controller {$controller}");
        }
        return call_user_func([$instance, $method]);
    }
}