<?php

namespace Tents\Core;

use Tents\Core\Exceptions\RouteNotFoundException;
use Tents\Core\Request;
use Tents\Core\Traits\Loggable;

class Router
{

    Use Loggable;

    public array $routes = [
        "GET" => [],
        "POST" => [],    
    ];

    public string $notFound = 'not_found';
    public string $internalError = 'internal_error';

    public function loadRoutes($path, $action, $method = "GET") {
        $this->routes[$method][$path] = $action;
    }

    public function getController($path, $http_method) {
        if (!$this -> exists($path, $http_method)) {
            throw new RouteNotFoundException("No existe ruta para este path");
        }
        return explode('@', $this -> routes[$http_method][$path]);
    }

    public function get($path, $action) {
        $this -> loadRoutes($path, $action, "GET");
    }
    
    public function post($path, $action) {
        $this -> loadRoutes($path, $action, "POST");
    }

    public function call($controller, $method, Request $request) {
        $controller_name = "Tents\\App\\Controllers\\{$controller}";
        $objController = new $controller_name($request);
        $objController->$method();
    }

    public function exists($path, $method) {
        return array_key_exists($path, $this -> routes[$method]);
    }

    public function direct(Request $request) {
        try {
            list($path, $http_method) = $request->route();
            list($controller, $method) = $this->getController($path, $http_method);
            $this -> logger
                  -> info("
                        Status Code: 200", 
                        [
                            "Path" => $path,
                            "Method" => $http_method
                        ]
                    );
            } catch (RouteNotFoundException $e) {
                list($controller, $method) = $this -> getController($this -> notFound, "GET");
                $this -> logger -> debug('Status Code:404 - Route Not Found', ["ERROR" => $e]);
            } finally {
                $this->call($controller, $method, $request);
            }
    }
} 