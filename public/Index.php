<?php

require __DIR__ . "/../src/bootstrap.php";

use Paw\App\Controllers\ErrorController;
use Paw\App\Controllers\PageController;
use Paw\Core\Router;

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$log->info("Petición a: {$path}");
$controller = new PageController; 

$router = new Router;
$router->loadRouters('/','PageController@index');
$router->loadRouters('/servicies','PageController@servicies');
$router->loadRouters('/contact','PageController@contact');
$router-> direct($path); 

if ($path == '/'){
    $controller -> index();
    $log->info('Respuesta exitosa: 200');
}else if ($path == '/servicies'){
    $controller-> servicies();
    $log->info('Respuesta exitosa: 200');
}else if ($path == '/contact'){
   $controller->contact();
   $log->info('Respuesta exitosa: 200');
}else{
    $controller = new ErrorController;
    $controller ->notFound();
    $log->info('Not found 404');
}
