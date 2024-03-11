<?php

require __DIR__ . "/../src/bootstrap.php";


use Paw\Core\Router;
use Paw\Core\Exceptions\RouteNotFoundException;

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$log->info("Petición a: {$path}");


$router = new Router;
$router->loadRouters('/','PageController@index');
$router->loadRouters('/servicies','PageController@servicies');
$router->loadRouters('/contact','PageController@contact');
$router->loadRouters('not_found', 'ErrorController@notFound');
$router->loadRouters('internal_error','ErrorController@internalError');

try {
    $router->direct($path);
}catch(RouteNotFoundException $e){
    $router->direct('not_found');
    $log->info('Not found 404', ["Path"=>$path]);
}catch(Exception $e){
    $router->direct('internal_error');
    $log->error('Status Code 500', ["Error"=>$e]);
}
$router-> direct($path); 
