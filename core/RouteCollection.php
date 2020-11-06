<?php

namespace core;

use core\request\RequestInterface;

class RouteCollection
{
    protected $routes = [];

    // 当前访问的路由
    protected $route_index = 0;

    public function getRoutes()
    {
        return $this->routes;
    }

    // 当前组
    public $currGroup = [];

    public function group(array $attributes, \Closure $callback)
    {
        $this->currGroup[] = $attributes;

        call_user_func($callback, $this);

        array_pop($this->currGroup);
    }

    protected function addSlash(&$uri)
    {
        return $uri[0] == '/' ?: $uri = '/' . $uri;
    }

    public function addRoute($method, $uri, $uses)
    {
        $prefix = '';
        $middleware = [];
        $namespace = '';
        $this->addSlash($uri);

        foreach ($this->currGroup as $group)
        {
            $prefix .= $group['prefix'] ?? false;
            if ($prefix) {
                $this->addSlash($prefix);
            }

            // 合并组中间件
            $middleware = $group['middleware'] ?? [];
            // 拼接组的命名空间
            $namespace .= $group['namespace'] ?? '';
        }

        $method = strtoupper($method);
        $uri = $prefix . $uri;
        $this->route_index = $method . $uri;
        $this->routes[$this->route_index] = [
            'method' => $method,
            'uri' => $uri,
            'action' => [
                'uses' => $uses,
                'middleware' => $middleware,
                'namespace' => $namespace
            ]
        ];
    }

    public function get($uri, $uses)
    {
        $this->addRoute('get', $uri, $uses);
        return $this;
    }

    public function post($uri, $uses)
    {
        $this->addRoute('post', $uri, $uses);
        return $this;
    }

    public function put($uri, $uses)
    {
        $this->addRoute('put', $uri, $uses);
        return $this;
    }

    public function delete($uri, $uses)
    {
        $this->addRoute('delete', $uri, $uses);
        return $this;
    }

    public function middleware($class)
    {
        $this->routes[$this->route_index]['action']['middleware'][] = $class;
        return $this;
    }

    public function getCurrRoute()
    {
        $routes = $this->getRoutes();
        $route_index = $this->route_index;

        if (isset($routes[$route_index])) {
            return $routes[$route_index];
        }

        $route_index .= '/';
        if (isset($routes[$route_index])) {
            return $routes[$route_index];
        }

        return false;
    }

    public function dispatch(RequestInterface $request)
    {
        $method = $request->getMethod();
        $uri = $request->getUri();
        $this->route_index = $method . $uri;

        $route = $this->getCurrRoute();
        if (!$route) {
            return 404;
        }

        $middleware = $route['action']['middleware'] ?? [];

        $routerDispatch = $route['action']['uses'];

        // 不是闭包 就是控制器了
        if (!$route['action']['uses'] instanceof \Closure) {
            $action = $route['action'];
            $uses = explode('@', $action['uses']);
            $controller = $action['namespace'] . '\\' . $uses[0];
            $method = $uses[1];
            /** @var Controller $controllerInstance */
            $controllerInstance = new $controller;
            $middleware = array_merge($middleware, $controllerInstance->getMiddleware());
            $routerDispatch = function ($request) use ($route, $controllerInstance, $method) {
                return $controllerInstance->callAction($method, [$request]);
            };
        }

        return app('pipeline')->create()->setClass($middleware)->run($routerDispatch)($request);
    }
}