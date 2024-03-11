<?php

namespace Paw\Core;

use Paw\Core\Exceptions\RouteNotFoundException;
class Router
{
    public array $routes;
    public function loadRouters($path,$action) 
    {
        $this->routes[$path] = $action;
    }
    public function direct($path)
    {
        if(!array_key_exists($path, $this->routes)){
            throw new RouteNotFoundException("No existe ruta para esta URL");
            
        }
        list($controller,$method) = explode('@',$this->routes[$path]);
            $controller_name = "Paw\\App\\Controllers\\{$controller}";
            $objContrller = new $controller_name;
            $objContrller-> $method();
        
    }
} 