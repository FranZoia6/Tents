<?php

namespace Paw\Core;

class Router
{
    public array $routes;
    public function loadRouters($path,$action) 
    {
        $this->routes[$path] = $action;
    }
    public function direct($path)
    {
        if(array_key_exists($path, $this->routes)){
            list($controller,$method) = explode('@',$this->routes[$path]);
            $controller_name = "Paw\\App\\Controllers\\{$controller}";
            $objContrller = new $controller_name;
            $objContrller-> $method();
        }
       // throw new RouteNotFoundExeption("No existe ruta para esta URL");
    }
} 